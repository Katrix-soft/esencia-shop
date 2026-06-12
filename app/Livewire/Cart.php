<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Esencia - Carrito de Compras')]
class Cart extends Component
{
    public $shippingCost = 4500;

    public function getItemsProperty()
    {
        $content = \Gloudemans\Shoppingcart\Facades\Cart::instance('default')->content();
        $items = [];
        foreach ($content as $item) {
            $stock = 0;
            if (is_numeric($item->id)) {
                $product = \App\Models\Product::find($item->id);
                $stock = $product ? (int) $product->stock : 0;
            }

            $items[] = [
                'rowId' => $item->rowId,
                'id' => $item->id,
                'name' => $item->name,
                'type' => $item->options->type ?? 'Fragancia',
                'size' => $item->options->size ?? '50ml',
                'price' => $item->price,
                'original_price' => $item->options->original_price ?? $item->price,
                'discount' => $item->options->discount ?? 0,
                'quantity' => $item->qty,
                'img' => $item->options->image ?? '',
                'has_stock_error' => (is_numeric($item->id) && $item->qty > $stock)
            ];
        }
        return $items;
    }

    public function increaseQuantity($itemId)
    {
        $cart = \Gloudemans\Shoppingcart\Facades\Cart::instance('default');
        $item = $cart->search(function ($cartItem, $rowId) use ($itemId) {
            return $cartItem->id == $itemId;
        })->first();

        if ($item) {
            if (is_numeric($itemId)) {
                $product = \App\Models\Product::find($itemId);
                $stock = $product ? (int) $product->stock : 0;

                if ($item->qty >= $stock) {
                    $this->dispatch('swal', [
                        'icon' => 'error',
                        'title' => 'Sin stock',
                        'text' => $product ? "Solo tenemos {$stock} unidades disponibles de {$product->name}." : 'Producto no disponible.'
                    ]);
                    return;
                }
            }
            $cart->update($item->rowId, $item->qty + 1);
        }
    }

    public function decreaseQuantity($itemId)
    {
        $cart = \Gloudemans\Shoppingcart\Facades\Cart::instance('default');
        $item = $cart->search(function ($cartItem, $rowId) use ($itemId) {
            return $cartItem->id == $itemId;
        })->first();

        if ($item && $item->qty > 1) {
            $cart->update($item->rowId, $item->qty - 1);
        }
    }

    public function removeItem($itemId)
    {
        $cart = \Gloudemans\Shoppingcart\Facades\Cart::instance('default');
        $item = $cart->search(function ($cartItem, $rowId) use ($itemId) {
            return $cartItem->id == $itemId;
        })->first();

        if ($item) {
            $cart->remove($item->rowId);
        }
    }

    public function getSubtotalProperty()
    {
        $subtotal = 0;
        foreach ($this->items as $item) {
            $stock = 0;
            if (is_numeric($item['id'])) {
                $product = \App\Models\Product::find($item['id']);
                $stock = $product ? (int) $product->stock : 0;
            }

            // Si la cantidad es válida o si es un item sin ID numérico (ej. Discovery Set), sumar.
            if (!is_numeric($item['id']) || $item['quantity'] <= $stock) {
                $subtotal += $item['price'] * $item['quantity'];
            }
        }
        return $subtotal;
    }

    public function getTotalProperty()
    {
        if (count($this->items) === 0) {
            return 0;
        }
        return $this->subtotal + $this->shippingCost;
    }

    public function getPointsProperty()
    {
        return round($this->subtotal / 100);
    }

    public function addDiscoverySet()
    {
        \Gloudemans\Shoppingcart\Facades\Cart::instance('default')->add(
            3, // Or unique ID
            'Discovery Set',
            1,
            32000,
            [
                'type' => 'Bosque Profundo', 
                'size' => '3 x viales 2ml', 
                'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDjaiTcbzPSfNSen_8KeCj9tg6snb-wsNFNsVOjUyihLc6tFrJr1zhdwjDNlZaNwS-o00FVl0ISAYTjni3cXfA2LADJrvJyJIP6ZQqrqtQ_vr5wiFqO792DJqbUWSfyzUE6S-wV6hT-KB1UUFu1IrD3vJ3rVQH2qxdfxhTFjq7orQaD-Uit0rCrpS-cLWt66ckUvsKy-Khnm_e_Npg8A3pyf6TkaUqGdsFQgeTWPSn3zVzdNw5eRQKhq_hLAkhW7oe-1xU7ZOGVqrA'
            ]
        );
    }

    public function checkout()
    {
        if (count($this->items) === 0) {
            return;
        }

        // Check stock for real products
        $hasValidItems = false;
        foreach ($this->items as $item) {
            $stock = 0;
            if (is_numeric($item['id'])) {
                $product = \App\Models\Product::find($item['id']);
                $stock = $product ? (int) $product->stock : 0;
            }

            if (!is_numeric($item['id']) || $item['quantity'] <= $stock) {
                $hasValidItems = true;
                break;
            }
        }

        if (!$hasValidItems) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Sin stock disponible',
                'text' => 'No tienes ningún producto con stock disponible para comprar.'
            ]);
            return;
        }

        if (auth()->check()) {
            return redirect()->route('shipping');
        }

        session()->put('url.intended', route('shipping'));
        $this->dispatch('swal', [
            'icon' => 'info',
            'title' => 'Inicia sesión',
            'text' => 'Para finalizar tu compra de forma segura y acumular tus semillas de regalo, inicia sesión o crea una cuenta en segundos.'
        ]);
        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.cart', [
            'items' => $this->items
        ]);
    }
}
