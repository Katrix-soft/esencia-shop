<?php

use Illuminate\Support\Facades\Route;
use KatrixSoft\Cart\Http\Controllers\MercadoPagoController;

/*
|--------------------------------------------------------------------------
| Rutas del paquete katrix-soft/cart
|--------------------------------------------------------------------------
| Estas rutas se cargan automáticamente via KatrixCartServiceProvider.
| Para personalizar los middlewares, publica la configuración con:
|   php artisan vendor:publish --tag=cart-config
*/

// ── Carrito y Checkout (requiere autenticación) ──────────────────────────
Route::middleware(['web', 'auth'])->group(function () {

    Route::get('/cart', function () {
        return view('cart::pages.cart');
    })->name('cart.index');

    Route::get('/checkout', function () {
        return view('cart::pages.checkout');
    })->name('checkout');

});

// ── Mercado Pago ─────────────────────────────────────────────────────────
Route::middleware(['web', 'auth'])->group(function () {
    Route::post('/mercadopago/process', [MercadoPagoController::class, 'processPayment'])
        ->name('mercadopago.process');
});

// Webhook MP: sin CSRF (viene de Mercado Pago, no del browser)
Route::post('/api/webhooks/mercadopago', [MercadoPagoController::class, 'handleWebhook'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->name('mercadopago.webhook');
