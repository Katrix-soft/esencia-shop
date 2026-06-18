<?php

namespace KatrixSoft\Cart;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class KatrixCartServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Merge default config
        $this->mergeConfigFrom(
            __DIR__ . '/../config/katrix-cart.php',
            'katrix-cart'
        );
    }

    public function boot(): void
    {
        // ── Vistas ──────────────────────────────────────────────────────────
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'cart');

        // ── Rutas ───────────────────────────────────────────────────────────
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // ── Migraciones ─────────────────────────────────────────────────────
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // ── Componentes Livewire ─────────────────────────────────────────────
        Livewire::component('cart::shopping-cart', \KatrixSoft\Cart\Livewire\ShoppingCart::class);
        Livewire::component('cart::checkout',      \KatrixSoft\Cart\Livewire\Checkout::class);

        // ── Publicables ──────────────────────────────────────────────────────
        if ($this->app->runningInConsole()) {
            // Config
            $this->publishes([
                __DIR__ . '/../config/katrix-cart.php' => config_path('katrix-cart.php'),
            ], 'cart-config');

            // Migraciones
            $this->publishes([
                __DIR__ . '/../database/migrations/' => database_path('migrations'),
            ], 'cart-migrations');

            // Vistas (opcional, para personalización)
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/cart'),
            ], 'cart-views');
        }
    }
}
