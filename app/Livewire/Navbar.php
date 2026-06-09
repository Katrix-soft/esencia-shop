<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class Navbar extends Component
{
    #[On('cart-updated')]
    public function refresh()
    {
        // Re-renders the navbar when cart changes
    }

    public function getCartCountProperty()
    {
        $cart = session()->get('cart', []);
        $count = 0;
        foreach ($cart as $item) {
            $count += $item['quantity'];
        }
        return $count;
    }

    public function logout()
    {
        \Illuminate\Support\Facades\Auth::logout();
        return redirect()->route('catalog');
    }

    public function render()
    {
        return view('livewire.navbar');
    }
}
