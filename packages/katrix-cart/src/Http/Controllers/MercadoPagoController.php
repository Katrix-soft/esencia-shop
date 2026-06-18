<?php

namespace KatrixSoft\Cart\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;

class MercadoPagoController extends Controller
{
    public function __construct()
    {
        MercadoPagoConfig::setAccessToken(config('katrix-cart.mercadopago.access_token'));
    }

    /**
     * Procesar pago con Payment Brick.
     */
    public function processPayment(Request $request): JsonResponse
    {
        $request->validate([
            'token'              => 'required|string',
            'issuer_id'          => 'required',
            'payment_method_id'  => 'required|string',
            'transaction_amount' => 'required|numeric',
            'installments'       => 'required|integer',
            'payer.email'        => 'required|email',
        ]);

        try {
            $client      = new PaymentClient();
            $paymentData = [
                'transaction_amount' => (float) $request->transaction_amount,
                'token'              => $request->token,
                'description'        => 'Compra #' . uniqid('order_'),
                'installments'       => (int) $request->installments,
                'payment_method_id'  => $request->payment_method_id,
                'issuer_id'          => (int) $request->issuer_id,
                'external_reference' => $request->input('external_reference', uniqid('order_')),
                'notification_url'   => url('/api/webhooks/mercadopago'),
                'payer'              => [
                    'email'          => $request->input('payer.email'),
                    'identification' => $request->input('payer.identification', []),
                ],
                'additional_info'    => [
                    'items' => $request->input('items', []),
                    'payer' => [
                        'first_name' => auth()->user()?->name ?? '',
                    ],
                ],
            ];

            $payment = $client->create($paymentData);

            return response()->json([
                'status'          => $payment->status,
                'status_detail'   => $payment->status_detail,
                'id'              => $payment->id,
                'payment_type_id' => $payment->payment_type_id,
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Manejar Webhooks enviados por Mercado Pago.
     */
    public function handleWebhook(Request $request): JsonResponse
    {
        \Illuminate\Support\Facades\Log::info('KatrixCart MP Webhook:', $request->all());

        $type   = $request->input('type');
        $action = $request->input('action');

        $paymentId = null;

        if ($type === 'payment' && $request->has('data.id')) {
            $paymentId = $request->input('data.id');
        } elseif (isset($action) && str_starts_with($action, 'payment.') && $request->has('data.id')) {
            $paymentId = $request->input('data.id');
        }

        if (! $paymentId) {
            return response()->json(['status' => 'ok']);
        }

        try {
            $client  = new PaymentClient();
            $payment = $client->get($paymentId);

            if ($payment->status !== 'approved') {
                return response()->json(['status' => 'ok']);
            }

            $monto       = $payment->transaction_amount;
            $descripcion = $payment->description ?? '';
            $email       = $payment->payer->email ?? null;

            $orderClass = \KatrixSoft\Cart\Models\Order::class;

            $order = $orderClass::where('payment_status', 'pending')
                ->where(function ($q) use ($descripcion, $monto, $email) {
                    $q->whereRaw("? LIKE CONCAT('%', CAST(id AS CHAR), '%')", [$descripcion])
                      ->orWhere(function ($q2) use ($monto, $email) {
                          $q2->where('total', $monto)
                             ->whereHas('user', fn($u) => $u->where('email', $email));
                      });
                })
                ->latest()
                ->first();

            if ($order) {
                $order->update([
                    'payment_status' => 'paid',
                    'mp_payment_id'  => $paymentId,
                    'paid_at'        => now(),
                ]);
                \Illuminate\Support\Facades\Log::info("KatrixCart: Orden #{$order->id} aprobada via webhook MP");
            }

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('KatrixCart MP Webhook error: ' . $e->getMessage());
            return response()->json(['error' => 'failed'], 500);
        }

        return response()->json(['status' => 'ok']);
    }
}
