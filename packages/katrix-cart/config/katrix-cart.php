<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Modelo de Stock
    |--------------------------------------------------------------------------
    | La clase que implementa StockableProduct::class para validar stock.
    | Debe tener los métodos getStock(), getId() y findForCart().
    */
    'stock_model' => \App\Models\Variant::class,

    /*
    |--------------------------------------------------------------------------
    | Modelo de Usuario
    |--------------------------------------------------------------------------
    | El modelo User de tu aplicación (para las relaciones de Address y Order).
    */
    'user_model' => \App\Models\User::class,

    /*
    |--------------------------------------------------------------------------
    | Ruta de inicio (home)
    |--------------------------------------------------------------------------
    | La ruta nombrada a la que se redirige al usuario luego de completar
    | la compra o al hacer clic en "Volver a la tienda".
    */
    'home_route' => 'welcome.index',

    /*
    |--------------------------------------------------------------------------
    | Ruta del carrito
    |--------------------------------------------------------------------------
    */
    'cart_route' => 'cart.index',

    /*
    |--------------------------------------------------------------------------
    | Formato de precio
    |--------------------------------------------------------------------------
    | Función utilizada para formatear precios. Puedes sobrescribirla
    | configurando un callable. Recibe el valor numérico y devuelve string.
    |
    | Ejemplo: fn($price) => '$ ' . number_format($price, 2, ',', '.')
    */
    'price_formatter' => null, // null = usa el formato por defecto

    /*
    |--------------------------------------------------------------------------
    | Moneda y símbolo
    |--------------------------------------------------------------------------
    */
    'currency'        => 'ARS',
    'currency_symbol' => '$',

    /*
    |--------------------------------------------------------------------------
    | Envío gratis
    |--------------------------------------------------------------------------
    | Si es true, el costo de envío se muestra como "GRATIS" y se cobra $0.
    */
    'free_shipping' => true,

    /*
    |--------------------------------------------------------------------------
    | Métodos de pago habilitados
    |--------------------------------------------------------------------------
    | Opciones: 'mercadopago', 'bank_transfer', 'cash'
    */
    'payment_methods' => ['mercadopago'],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Mercado Pago
    |--------------------------------------------------------------------------
    */
    'mercadopago' => [
        'public_key'   => env('MP_PUBLIC_KEY', ''),
        'access_token' => env('MP_ACCESS_TOKEN', ''),
        'locale'       => 'es-AR',
    ],

    /*
    |--------------------------------------------------------------------------
    | Datos bancarios (para transferencia)
    |--------------------------------------------------------------------------
    */
    'bank_info' => [
        'bank'    => env('BANK_NAME', ''),
        'cbu'     => env('BANK_CBU', ''),
        'alias'   => env('BANK_ALIAS', ''),
        'cuit'    => env('BANK_CUIT', ''),
        'titular' => env('BANK_TITULAR', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Instancia del carrito
    |--------------------------------------------------------------------------
    | Nombre de la instancia del carrito de hardevine/shoppingcart.
    */
    'cart_instance' => 'shopping',

    /*
    |--------------------------------------------------------------------------
    | Límite de órdenes por mes (tenant)
    |--------------------------------------------------------------------------
    | Si usas un modelo de suscripción/plan, el paquete puede verificar si
    | el tenant ha alcanzado su límite mensual de órdenes.
    | Poner en null para deshabilitar esta verificación.
    */
    'tenant_orders_limit' => null,

];
