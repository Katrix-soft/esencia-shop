<?php

namespace App\Livewire;

use Livewire\Component;

class ProductCard extends Component
{
    public $product;

    public function mount($product)
    {
        $this->product = $product;
    }

    public function addToCart()
    {
        $cart = \Gloudemans\Shoppingcart\Facades\Cart::instance('default');
        $item = $cart->search(function ($cartItem, $rowId) {
            return $cartItem->id === $this->product->id;
        })->first();

        $stock = $this->product ? (int) $this->product->stock : 0;

        if ($item) {
            if ($item->qty >= $stock) {
                $this->dispatch('swal', [
                    'icon' => 'error',
                    'title' => 'Sin stock',
                    'text' => "Solo tenemos {$stock} unidades disponibles de {$this->product->name}."
                ]);
                return;
            }
            $cart->update($item->rowId, $item->qty + 1);
        } else {
            if ($stock < 1) {
                $this->dispatch('swal', [
                    'icon' => 'error',
                    'title' => 'Agotado',
                    'text' => "{$this->product->name} está agotado."
                ]);
                return;
            }
            $cart->add(
                $this->product->id,
                $this->product->name,
                1,
                $this->product->discounted_price,
                [
                    'image' => $this->product->image,
                    'original_price' => $this->product->price,
                    'discount' => $this->product->discount
                ]
            );
        }
        
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Agregado',
            'text' => "{$this->product->name} añadido al carrito."
        ]);
        $this->dispatch('cart-updated');
    }

    public function render()
    {
        return view('livewire.product-card');
    }
}
