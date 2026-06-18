<?php

if (! function_exists('cart_format_price')) {
    /**
     * Formatea un valor numérico como precio.
     * Si se configura 'price_formatter' en katrix-cart.php, usa esa función.
     * De lo contrario usa el formato por defecto: "$ 1.234,56"
     */
    function cart_format_price(float|int $price): string
    {
        $formatter = config('katrix-cart.price_formatter');

        if (is_callable($formatter)) {
            return $formatter($price);
        }

        $symbol = config('katrix-cart.currency_symbol', '$');

        return $symbol . ' ' . number_format($price, 2, ',', '.');
    }
}

if (! function_exists('cart_instance')) {
    /**
     * Devuelve la instancia configurada del carrito de hardevine/shoppingcart.
     */
    function cart_instance(): string
    {
        return config('katrix-cart.cart_instance', 'shopping');
    }
}

if (! function_exists('cart_stock_model')) {
    /**
     * Devuelve la clase del modelo de stock configurada.
     */
    function cart_stock_model(): string
    {
        return config('katrix-cart.stock_model');
    }
}
