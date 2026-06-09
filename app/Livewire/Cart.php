<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Esencia - Carrito de Compras')]
class Cart extends Component
{
    // Mocking cart state for interactivity, loaded from session
    public $items = [];
    public $shippingCost = 4500;

    public function mount()
    {
        if (session()->has('cart')) {
            $this->items = session()->get('cart');
        } else {
            $this->items = [
                [
                    'id' => 1,
                    'name' => 'Decant Santal 33',
                    'type' => 'Extracto',
                    'size' => '10ml',
                    'price' => 25000,
                    'quantity' => 1,
                    'img' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDbxInPO8OVk471uH5St2aeBPUxT8_y4aPylZjdLiCisogy12jDKE0eJ321T9BvhY6ddn313IkQ_ci-dZ-H2D1be6zmIzjjhPYzB35ST-wLLH3yvqQ7O3ziOZQ2MAsig1cv5ZhJQ2FGT5C4gXOApL_Tj83GO47GB8EFbwtBrIjKfTq6b7ahjsjBFMYNxWSTcJDOYLoEVy0YL0iDPoO9x7cRKDJc4PhaaZiwfiS579vlQ0Cqkl_XhcJCXR3wwRgJMvcGg8EIvH61J5w'
                ],
                [
                    'id' => 2,
                    'name' => 'Decant Baccarat Rouge',
                    'type' => 'EDP',
                    'size' => '5ml',
                    'price' => 32000,
                    'quantity' => 1,
                    'img' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDkd86VqaTTXdLZbhBi6DX0-QkTt7recLHsKpzhyvRu6NDRINeZ78Z5LfjpbWEy77zHGNTtim-InM59yDZOLxUMHGv_P_7Ekk1Lr0d8ClDH0BNvnB2QlIKX30wOQc3OZW7hSl0e5k7xb97Rsjg2WoVRqiLwoh9lFSelhOi0jP3gUPYTIZ2pcLlZ90K9bL5dwGf3mXYItL4ZznxilvdyNLkcYfyWVqGQskFj82dNd4cqyDiSRUkYmZVnPeqQwHMywDf78pFWMINP15o'
                ]
            ];
            $this->saveCart();
        }
    }

    private function saveCart()
    {
        session()->put('cart', $this->items);
    }

    public function increaseQuantity($itemId)
    {
        foreach ($this->items as &$item) {
            if ($item['id'] === $itemId) {
                $item['quantity']++;
                break;
            }
        }
        $this->saveCart();
    }

    public function decreaseQuantity($itemId)
    {
        foreach ($this->items as &$item) {
            if ($item['id'] === $itemId && $item['quantity'] > 1) {
                $item['quantity']--;
                break;
            }
        }
        $this->saveCart();
    }

    public function removeItem($itemId)
    {
        $this->items = array_filter($this->items, function ($item) use ($itemId) {
            return $item['id'] !== $itemId;
        });
        // reset array keys to maintain JSON array structure
        $this->items = array_values($this->items);
        $this->saveCart();
    }

    public function getSubtotalProperty()
    {
        $subtotal = 0;
        foreach ($this->items as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        return $subtotal;
    }

    public function getTotalProperty()
    {
        if (count($this->items) === 0) {
            return 0;
        }
        return $this->getSubtotalProperty() + $this->shippingCost;
    }

    public function getPointsProperty()
    {
        return round($this->getSubtotalProperty() / 100);
    }

    public function addDiscoverySet()
    {
        // Add cross selling product to cart
        $this->items[] = [
            'id' => 3,
            'name' => 'Discovery Set',
            'type' => 'Bosque Profundo',
            'size' => '3 x viales 2ml',
            'price' => 32000,
            'quantity' => 1,
            'img' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDjaiTcbzPSfNSen_8KeCj9tg6snb-wsNFNsVOjUyihLc6tFrJr1zhdwjDNlZaNwS-o00FVl0ISAYTjni3cXfA2LADJrvJyJIP6ZQqrqtQ_vr5wiFqO792DJqbUWSfyzUE6S-wV6hT-KB1UUFu1IrD3vJ3rVQH2qxdfxhTFjq7orQaD-Uit0rCrpS-cLWt66ckUvsKy-Khnm_e_Npg8A3pyf6TkaUqGdsFQgeTWPSn3zVzdNw5eRQKhq_hLAkhW7oe-1xU7ZOGVqrA'
        ];
        $this->saveCart();
    }

    public function checkout()
    {
        if (count($this->items) === 0) {
            return;
        }

        $this->saveCart();

        if (auth()->check()) {
            return redirect()->route('shipping');
        }

        session()->put('url.intended', route('shipping'));
        session()->flash('checkout_warning', 'Para finalizar tu compra de forma segura y acumular tus semillas de regalo, inicia sesión o crea una cuenta en segundos.');
        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.cart');
    }
}
