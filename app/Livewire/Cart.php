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
        if (cache('plan_id', '') === 'toilette') {
            return 0;
        }
        return round($this->subtotal / 100);
    }

    public function getSuggestionProperty()
    {
        if (cache('plan_id', '') === 'toilette') {
            return null;
        }

        $cartProductIds = collect($this->items)->filter(fn($i) => is_numeric($i['id']))->pluck('id')->toArray();
        $cartPackIds = collect($this->items)->filter(fn($i) => str_starts_with((string)$i['id'], 'pack_'))->pluck('id')->map(fn($id) => str_replace('pack_', '', $id))->toArray();

        // 1. Intentar sugerir un Pack que no esté en el carrito
        $pack = \App\Models\Pack::whereNotIn('id', $cartPackIds)->inRandomOrder()->first();
        if ($pack) {
            $pack->is_pack_model = true;
            return $pack;
        }

        // 2. Si no hay packs, sugerir el producto más popular que no esté en el carrito
        $product = \App\Models\Product::whereNotIn('id', $cartProductIds)->orderByDesc('popularity')->first();
        if ($product) {
            $product->is_pack_model = false;
            return $product;
        }

        return null;
    }

    public function addSuggestion($id, $isPack)
    {
        if ($isPack) {
            $pack = \App\Models\Pack::find($id);
            if ($pack) {
                \Gloudemans\Shoppingcart\Facades\Cart::instance('default')->add(
                    'pack_' . $pack->id,
                    $pack->name,
                    1,
                    $pack->discounted_price,
                    [
                        'type' => 'Pack Exclusivo',
                        'size' => 'Colección',
                        'image' => $pack->image ? asset('storage/'.$pack->image) : ''
                    ]
                );
            }
        } else {
            $product = \App\Models\Product::find($id);
            if ($product && $product->stock > 0) {
                \Gloudemans\Shoppingcart\Facades\Cart::instance('default')->add(
                    $product->id,
                    $product->name,
                    1,
                    $product->discounted_price,
                    [
                        'type' => $product->category ? $product->category->name : 'Fragancia',
                        'size' => '50ml',
                        'image' => $product->image ? asset('storage/'.$product->image) : '',
                        'original_price' => $product->price,
                        'discount' => $product->discount
                    ]
                );
            } elseif ($product) {
                $this->dispatch('swal', [
                    'icon' => 'error',
                    'title' => 'Sin stock',
                    'text' => 'El producto sugerido ya no tiene stock disponible.'
                ]);
                return;
            }
        }
        
        $this->dispatch('cart-updated');
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
