<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MercadoPagoWebhookTest extends TestCase
{
    public function test_webhook_returns_success_when_signature_is_correct()
    {
        // 1. Configurar clave secreta
        $secret = 'test_secret_key';
        Config::set('services.mercadopago.webhook_secret', $secret);
        Config::set('services.mercadopago.access_token', 'test_access_token');

        // Simulamos la llamada HTTP externa a Mercado Pago
        Http::fake([
            'https://api.mercadopago.com/v1/payments/*' => Http::response([
                'status' => 'approved',
                'status_detail' => 'accredited',
                'transaction_amount' => 100.00
            ], 200)
        ]);

        $dataId = '1234567890';
        $xRequestId = 'request-id-xyz';
        $ts = '1742505638683';

        // Construir manifest string con data.id en minúscula
        $manifest = "id:" . strtolower($dataId) . ";request-id:{$xRequestId};ts:{$ts};";
        $signature = hash_hmac('sha256', $manifest, $secret);

        $xSignatureHeader = "ts={$ts},v1={$signature}";

        // Enviar POST con los parámetros y cabeceras simulados
        $response = $this->withHeaders([
            'x-signature' => $xSignatureHeader,
            'x-request-id' => $xRequestId,
        ])->postJson("/mercadopago/webhook?data.id={$dataId}&type=payment", [
            'action' => 'payment.created',
            'type' => 'payment'
        ]);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'recibido']);
    }

    public function test_webhook_returns_error_when_signature_is_incorrect()
    {
        $secret = 'test_secret_key';
        Config::set('services.mercadopago.webhook_secret', $secret);

        $dataId = '1234567890';
        $xRequestId = 'request-id-xyz';
        $ts = '1742505638683';

        $xSignatureHeader = "ts={$ts},v1=invalid_signature_hash";

        $response = $this->withHeaders([
            'x-signature' => $xSignatureHeader,
            'x-request-id' => $xRequestId,
        ])->postJson("/mercadopago/webhook?data.id={$dataId}&type=payment", [
            'action' => 'payment.created',
            'type' => 'payment'
        ]);

        $response->assertStatus(400);
        $response->assertJson(['error' => 'Firma inválida']);
    }
}
