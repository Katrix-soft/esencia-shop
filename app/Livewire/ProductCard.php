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
        $cart = session()->get('cart', []);
        
        $found = false;
        foreach ($cart as &$item) {
            if ($item['id'] === $this->product['id']) {
                $item['quantity']++;
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            $cart[] = [
                'id' => $this->product['id'],
                'name' => $this->product['name'],
                'type' => $this->product['family'],
                'size' => 'Decant 10ml',
                'price' => $this->product['price'] * 1000, // multiply to have realistic ARS prices matching layout
                'quantity' => 1,
                'img' => $this->product['image'],
            ];
        }
        
        session()->put('cart', $cart);
        $this->dispatch('cart-updated');
    }

    public function render()
    {
        return view('livewire.product-card');
    }
}
