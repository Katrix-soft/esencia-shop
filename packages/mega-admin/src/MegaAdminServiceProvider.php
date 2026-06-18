<?php

namespace KatrixSoft\MegaAdmin;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class MegaAdminServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Registrar configuraciones si son necesarias
    }

    public function boot()
    {
        // Cargar rutas
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // Cargar vistas
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'mega-admin');

        // Cargar migraciones
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Publicar assets o vistas (opcional)
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/mega-admin'),
        ], 'mega-admin-views');

        // Registrar componentes de Livewire (ejemplo)
        // Livewire::component('mega-admin::dashboard', \KatrixSoft\MegaAdmin\Livewire\Dashboard::class);
    }
}
