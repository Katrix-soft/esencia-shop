<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Catalog;
use App\Livewire\Packs;
use App\Livewire\PerfilOlfativo;
use App\Livewire\Cart;
use App\Livewire\Shipping;
use App\Livewire\Auth\Login;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Client\Portal as ClientPortal;

use App\Http\Controllers\MercadoPagoWebhookController;

Route::get('/', Catalog::class)->name('catalog');
Route::get('/packs', Packs::class)->name('packs');
Route::get('/perfil-olfativo', PerfilOlfativo::class)->name('perfil-olfativo');
Route::get('/carrito', Cart::class)->name('cart');
Route::get('/envio', Shipping::class)->name('shipping');
Route::get('/login', Login::class)->name('login');
Route::get('/admin/dashboard', AdminDashboard::class)->name('admin.dashboard');
Route::get('/mi-cuenta', ClientPortal::class)->name('client.portal');

Route::post('/mercadopago/webhook', [MercadoPagoWebhookController::class, 'handle'])->name('mercadopago.webhook');


