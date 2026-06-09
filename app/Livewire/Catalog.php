<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Esencia - Catálogo')]
class Catalog extends Component
{
    public $selectedFamilies = [];
    public $selectedFormats = [];

    // Dummy product data based on the HTML provided
    public function getProductsProperty()
    {
        if (!session()->has('admin_products')) {
            session()->put('admin_products', [
                [
                    'id' => 1,
                    'name' => 'Santal Raíz',
                    'description' => 'Sándalo cremoso, cedro de Virginia y un toque de cardamomo terroso.',
                    'price' => 35000,
                    'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuALCeIr5724Uy4nrTwpBaqkw4YCpL3i1HLda7MZhylrqTsXkunwz3vj8zIG1QX6l4fGAwEnAZcny6hgMhYvKpu4jyVW4YKnkheqK1n9oOGdZLHzY-ZQbQ3jlclrLLND-zdL4r79Twa8kTVXX3oQrrtDZdGL3pTz1qkH_sktQuZzBgPaazhgvARwQUYOC5uHuGe1h_SZ_gHthvLRY13gLyCg19-FpcSIFP2yY3ChqPVDEREwku3NqrEwp4CglbcwnyRo_V3dvjJijxw',
                    'family' => 'Amaderado',
                    'family_class' => 'bg-secondary-container text-on-secondary-container',
                    'wood' => 70,
                    'citrus' => 20,
                    'floral' => 10,
                    'in_stock' => true
                ],
                [
                    'id' => 2,
                    'name' => 'Brisa de Neroli',
                    'description' => 'Flor de azahar, mandarina verde y almizcle blanco suave.',
                    'price' => 28000,
                    'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDkd86VqaTTXdLZbhBi6DX0-QkTt7recLHsKpzhyvRu6NDRINeZ78Z5LfjpbWEy77zHGNTtim-InM59yDZOLxUMHGv_P_7Ekk1Lr0d8ClDH0BNvnB2QlIKX30wOQc3OZW7hSl0e5k7xb97Rsjg2WoVRqiLwoh9lFSelhOi0jP3gUPYTIZ2pcLlZ90K9bL5dwGf3mXYItL4ZznxilvdyNLkcYfyWVqGQskFj82dNd4cqyDiSRUkYmZVnPeqQwHMywDf78pFWMINP15o',
                    'family' => 'Cítrico',
                    'family_class' => 'bg-primary-container text-on-primary-container',
                    'wood' => 15,
                    'citrus' => 70,
                    'floral' => 15,
                    'in_stock' => true
                ],
                [
                    'id' => 3,
                    'name' => 'Ámbar Tierra',
                    'description' => 'Ámbar gris cálido, vainilla orgánica y resina de ládano.',
                    'price' => 42000,
                    'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDanohCFoATw9fj8u5l6UX6M-ok5a52c0GsYDZObc7vjXBPiM_dCBa0Z676fqgWOJdLLFKKiYyJ-JIr6vzzpM54dJ-cAbEe7XcltBxmVRhimiXrh0IwhoTJnvhpA_V2Qs5qrWq0AZYBmVJ4otHQ4Hu6Wrz7wZ9amoIJL_C3bUUhWiRB52ev3Wp6hZforkF3NL_D1JAW9qKu2rH2EXHGAK78V3Ve-Wjm0WfF2UbRM8khPFQUwZZvwPgpjruXmx5w8LcbkU9F8ujhmeU',
                    'family' => 'Oriental',
                    'family_class' => 'bg-tertiary-container text-on-tertiary-container',
                    'wood' => 30,
                    'citrus' => 10,
                    'floral' => 60,
                    'in_stock' => true
                ]
            ]);
        }

        $allProducts = session()->get('admin_products');

        // Apply filters if any are selected
        return collect($allProducts)->filter(function ($product) {
            $stockMatch = $product['in_stock'] ?? false;
            $familyMatch = empty($this->selectedFamilies) || in_array($product['family'], $this->selectedFamilies);
            return $stockMatch && $familyMatch;
        })->values()->toArray();
    }

    public function render()
    {
        return view('livewire.catalog');
    }
}
