<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class Navbar extends Component
{
    public $searchQuery = '';

    #[On('cart-updated')]
    public function refresh()
    {
        // Re-renders the navbar when cart changes
    }

    public function performSearch()
    {
        if (!empty(trim($this->searchQuery))) {
            $this->redirect(route('catalog', ['search' => trim($this->searchQuery)]), navigate: true);
        } else {
            $this->redirect(route('catalog'), navigate: true);
        }
    }

    public function getCartCountProperty()
    {
        return \Gloudemans\Shoppingcart\Facades\Cart::instance('default')->count();
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
