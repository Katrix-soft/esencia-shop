<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MercadoPagoWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // 1. Obtener la firma y el ID de la solicitud de los encabezados
        $xSignature = $request->header('x-signature');
        $xRequestId = $request->header('x-request-id');

        // 2. Extraer el parámetro "data.id" y "type" de la URL sin que PHP los altere
        $dataId = '';
        $type = $request->query('type');
        $queryString = $request->server('QUERY_STRING');
        if ($queryString) {
            foreach (explode('&', $queryString) as $param) {
                $parts = explode('=', $param, 2);
                if (count($parts) === 2 && urldecode($parts[0]) === 'data.id') {
                    $dataId = urldecode($parts[1]);
                    break;
                }
            }
        }

        // Si no se encuentra en query params, intentar del cuerpo de la petición
        if (empty($dataId)) {
            $dataId = $request->input('data.id') ?? $request->input('id');
        }
        if (empty($type)) {
            $type = $request->input('type');
        }

        Log::info('Mercado Pago Webhook recibido', [
            'type' => $type,
            'data_id' => $dataId,
            'x_request_id' => $xRequestId,
        ]);

        // 3. Validar origen de la notificación utilizando la clave secreta
        $secret = config('services.mercadopago.webhook_secret');
        if ($secret && $xSignature && $dataId) {
            // Extraer timestamp (ts) y hash (v1) del encabezado x-signature
            $ts = '';
            $hash = '';
            $parts = explode(',', $xSignature);
            foreach ($parts as $part) {
                $keyValue = explode('=', $part, 2);
                if (count($keyValue) === 2) {
                    $k = trim($keyValue[0]);
                    $v = trim($keyValue[1]);
                    if ($k === 'ts') {
                        $ts = $v;
                    } elseif ($k === 'v1') {
                        $hash = $v;
                    }
                }
            }

            // Construir el manifest string con data.id en minúsculas
            $dataIdLower = strtolower($dataId);
            $manifest = "id:{$dataIdLower};request-id:{$xRequestId};ts:{$ts};";

            // Crear la contraclave HMAC SHA256
            $sha = hash_hmac('sha256', $manifest, $secret);

            if ($sha !== $hash) {
                Log::warning('Mercado Pago Webhook: validación de firma fallida', [
                    'manifest' => $manifest,
                    'generated' => $sha,
                    'received' => $hash,
                ]);
                return response()->json(['error' => 'Firma inválida'], 400);
            }

            Log::info('Mercado Pago Webhook: validación de firma exitosa.');
        }

        // 4. Obtener los detalles del recurso desde Mercado Pago
        if ($dataId && $type) {
            $accessToken = config('services.mercadopago.access_token');
            $url = null;

            if ($type === 'payment') {
                $url = "https://api.mercadopago.com/v1/payments/{$dataId}";
            } elseif ($type === 'merchant_order') {
                $url = "https://api.mercadopago.com/v1/merchant_orders/{$dataId}";
            } elseif ($type === 'order') {
                $url = "https://api.mercadopago.com/v1/orders/{$dataId}";
            }

            if ($url) {
                try {
                    $response = Http::withToken($accessToken)->get($url);

                    if ($response->successful()) {
                        $details = $response->json();
                        Log::info("Detalles del recurso de Mercado Pago ({$type}) obtenidos con éxito", [
                            'status' => $details['status'] ?? 'unknown',
                            'status_detail' => $details['status_detail'] ?? 'unknown',
                            'transaction_amount' => $details['transaction_amount'] ?? 'unknown',
                        ]);

                        // Update local order status if external reference is set
                        $status = $details['status'] ?? null;
                        $externalRef = $details['external_reference'] ?? null;
                        if ($status === 'approved' && $externalRef) {
                            $orders = session()->get('admin_orders', []);
                            foreach ($orders as &$o) {
                                if ($o['id'] === $externalRef) {
                                    $o['status'] = 'Pagado';
                                    break;
                                }
                            }
                            session()->put('admin_orders', $orders);
                        }
                    } else {
                        Log::error("Error al obtener los detalles del recurso desde Mercado Pago ({$type})", [
                            'url' => $url,
                            'status' => $response->status(),
                            'body' => $response->body(),
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error("Excepción al consultar el recurso de Mercado Pago ({$type})", [
                        'message' => $e->getMessage(),
                    ]);
                }
            }
        }

        // 5. Confirmar recepción devolviendo HTTP 200 (OK)
        return response()->json(['status' => 'recibido'], 200);
    }
}
