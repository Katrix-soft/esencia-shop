<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Administración & CRM - Esencia')]
class Dashboard extends Component
{
    public $activeTab = 'crm';
    public $searchCustomer = '';
    public $searchProduct = '';
    public $filterOrderStatus = 'all';

    // Estado del Formulario de Creación de Producto
    public $newName = '';
    public $newPrice = 0;
    public $newDescription = '';
    public $newFamily = 'Amaderado';
    public $newWood = 50;
    public $newCitrus = 30;
    public $newFloral = 20;

    // Estado de Edición de Producto
    public $editingProductId = null;
    public $editName = '';
    public $editPrice = 0;
    public $editDescription = '';
    public $editWood = 50;
    public $editCitrus = 30;
    public $editFloral = 20;

    public function mount()
    {
        // Forzar autenticación de admin (simulada)
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $this->initializeData();
    }

    private function initializeData()
    {
        // Inicializar Productos en sesión si no existen
        if (!session()->has('admin_products')) {
            session()->put('admin_products', [
                [
                    'id' => 1,
                    'name' => 'Santal Raíz',
                    'description' => 'Sándalo cremoso, cedro de Virginia y un toque de cardamomo terroso.',
                    'price' => 35.00,
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
                    'price' => 28.00,
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
                    'price' => 42.00,
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

        // Inicializar Clientes en sesión si no existen
        if (!session()->has('admin_customers')) {
            session()->put('admin_customers', [
                [
                    'id' => 1,
                    'name' => 'Carlos Valenzuela',
                    'email' => 'carlos@valenzuela.com',
                    'profile' => 'Amaderado (75%)',
                    'semillas' => 840,
                    'status' => 'Frecuente',
                    'purchases_count' => 4,
                    'total_spent' => 125000,
                ],
                [
                    'id' => 2,
                    'name' => 'Sofía Ortega',
                    'email' => 'sofia.ortega@gmail.com',
                    'profile' => 'Cítrico (70%)',
                    'semillas' => 350,
                    'status' => 'Nuevo',
                    'purchases_count' => 1,
                    'total_spent' => 28000,
                ],
                [
                    'id' => 3,
                    'name' => 'Mateo Fernández',
                    'email' => 'mfernandez@outlook.com',
                    'profile' => 'Oriental (60%)',
                    'semillas' => 1120,
                    'status' => 'Embajador',
                    'purchases_count' => 8,
                    'total_spent' => 342000,
                ],
                [
                    'id' => 4,
                    'name' => 'Laura Paz',
                    'email' => 'laura.paz@gmail.com',
                    'profile' => 'No Calculado',
                    'semillas' => 0,
                    'status' => 'Invitado',
                    'purchases_count' => 0,
                    'total_spent' => 0,
                ]
            ]);
        }

        // Inicializar Pedidos en sesión si no existen
        if (!session()->has('admin_orders')) {
            session()->put('admin_orders', [
                [
                    'id' => 'ESP-8921',
                    'customer' => 'Carlos Valenzuela',
                    'items' => '1x Santal Raíz (10ml)',
                    'total' => 35000,
                    'status' => 'Enviado',
                    'date' => '2026-06-05',
                ],
                [
                    'id' => 'ESP-8922',
                    'customer' => 'Sofía Ortega',
                    'items' => '1x Brisa de Neroli (10ml)',
                    'total' => 28000,
                    'status' => 'Pagado',
                    'date' => '2026-06-06',
                ],
                [
                    'id' => 'ESP-8923',
                    'customer' => 'Mateo Fernández',
                    'items' => '2x Ámbar Tierra (10ml)',
                    'total' => 84000,
                    'status' => 'Pendiente',
                    'date' => '2026-06-07',
                ]
            ]);
        }
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    // --- ACCIONES DE CLIENTES / CRM ---
    public function getCustomersProperty()
    {
        $all = session()->get('admin_customers', []);
        if (empty($this->searchCustomer)) {
            return $all;
        }

        return array_filter($all, function ($customer) {
            return str_contains(strtolower($customer['name']), strtolower($this->searchCustomer)) ||
                   str_contains(strtolower($customer['email']), strtolower($this->searchCustomer));
        });
    }

    public function getScentInsightsProperty()
    {
        $customers = session()->get('admin_customers', []);
        $wood = 0;
        $citrus = 0;
        $floral = 0;
        $total = 0;

        foreach ($customers as $c) {
            if ($c['profile'] !== 'No Calculado') {
                $total++;
                if (str_contains($c['profile'], 'Amaderado')) {
                    $wood++;
                } elseif (str_contains($c['profile'], 'Cítrico')) {
                    $citrus++;
                } else {
                    $floral++;
                }
            }
        }

        if ($total === 0) return ['wood' => 33, 'citrus' => 33, 'floral' => 34];

        return [
            'wood' => round(($wood / $total) * 100),
            'citrus' => round(($citrus / $total) * 100),
            'floral' => round(($floral / $total) * 100)
        ];
    }

    // --- ACCIONES DE PRODUCTOS ---
    public function getProductsProperty()
    {
        $all = session()->get('admin_products', []);
        if (empty($this->searchProduct)) {
            return $all;
        }

        return array_filter($all, function ($product) {
            return str_contains(strtolower($product['name']), strtolower($this->searchProduct)) ||
                   str_contains(strtolower($product['family']), strtolower($this->searchProduct));
        });
    }

    public function toggleStock($productId)
    {
        $products = session()->get('admin_products', []);
        foreach ($products as &$p) {
            if ($p['id'] == $productId) {
                $p['in_stock'] = !$p['in_stock'];
                break;
            }
        }
        session()->put('admin_products', $products);
        session()->flash('product_success', 'El estado del stock se actualizó correctamente.');
    }

    public function addProduct()
    {
        $this->validate([
            'newName' => 'required|min:3',
            'newPrice' => 'required|numeric|min:1',
            'newDescription' => 'required',
        ]);

        $products = session()->get('admin_products', []);
        $newId = count($products) > 0 ? max(array_column($products, 'id')) + 1 : 1;

        $familyClasses = [
            'Amaderado' => 'bg-secondary-container text-on-secondary-container',
            'Cítrico' => 'bg-primary-container text-on-primary-container',
            'Floral' => 'bg-tertiary-container text-on-tertiary-container',
            'Oriental' => 'bg-tertiary-container text-on-tertiary-container',
        ];

        $class = $familyClasses[$this->newFamily] ?? 'bg-secondary-container text-on-secondary-container';

        $newProduct = [
            'id' => $newId,
            'name' => $this->newName,
            'description' => $this->newDescription,
            'price' => (float)$this->newPrice / 1000,
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDkd86VqaTTXdLZbhBi6DX0-QkTt7recLHsKpzhyvRu6NDRINeZ78Z5LfjpbWEy77zHGNTtim-InM59yDZOLxUMHGv_P_7Ekk1Lr0d8ClDH0BNvnB2QlIKX30wOQc3OZW7hSl0e5k7xb97Rsjg2WoVRqiLwoh9lFSelhOi0jP3gUPYTIZ2pcLlZ90K9bL5dwGf3mXYItL4ZznxilvdyNLkcYfyWVqGQskFj82dNd4cqyDiSRUkYmZVnPeqQwHMywDf78pFWMINP15o',
            'family' => $this->newFamily,
            'family_class' => $class,
            'wood' => (int)$this->newWood,
            'citrus' => (int)$this->newCitrus,
            'floral' => (int)$this->newFloral,
            'in_stock' => true
        ];

        $products[] = $newProduct;
        session()->put('admin_products', $products);

        // Reset fields
        $this->newName = '';
        $this->newPrice = 0;
        $this->newDescription = '';
        $this->newWood = 50;
        $this->newCitrus = 30;
        $this->newFloral = 20;

        session()->flash('product_success', 'Producto agregado con éxito al catálogo.');
    }

    public function startEdit($productId)
    {
        $products = session()->get('admin_products', []);
        foreach ($products as $p) {
            if ($p['id'] == $productId) {
                $this->editingProductId = $productId;
                $this->editName = $p['name'];
                $this->editPrice = $p['price'] * 1000;
                $this->editDescription = $p['description'];
                $this->editWood = $p['wood'];
                $this->editCitrus = $p['citrus'];
                $this->editFloral = $p['floral'];
                break;
            }
        }
    }

    public function saveEdit()
    {
        $this->validate([
            'editName' => 'required|min:3',
            'editPrice' => 'required|numeric|min:1',
            'editDescription' => 'required',
        ]);

        $products = session()->get('admin_products', []);
        foreach ($products as &$p) {
            if ($p['id'] == $this->editingProductId) {
                $p['name'] = $this->editName;
                $p['price'] = (float)$this->editPrice / 1000;
                $p['description'] = $this->editDescription;
                $p['wood'] = (int)$this->editWood;
                $p['citrus'] = (int)$this->editCitrus;
                $p['floral'] = (int)$this->editFloral;
                break;
            }
        }
        session()->put('admin_products', $products);
        $this->editingProductId = null;
        session()->flash('product_success', 'Producto editado con éxito.');
    }

    public function cancelEdit()
    {
        $this->editingProductId = null;
    }

    // --- ACCIONES DE PEDIDOS ---
    public function getOrdersProperty()
    {
        $all = session()->get('admin_orders', []);
        
        if ($this->filterOrderStatus !== 'all') {
            $all = array_filter($all, function ($order) {
                return $order['status'] === $this->filterOrderStatus;
            });
        }

        return $all;
    }

    public function updateOrderStatus($orderId, $newStatus)
    {
        $orders = session()->get('admin_orders', []);
        foreach ($orders as &$o) {
            if ($o['id'] === $orderId) {
                $o['status'] = $newStatus;
                break;
            }
        }
        session()->put('admin_orders', $orders);
        session()->flash('order_success', "Pedido {$orderId} actualizado a {$newStatus}.");
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
