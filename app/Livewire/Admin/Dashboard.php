<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;

#[Title('Administración & CRM - Esencia')]
class Dashboard extends Component
{
    use WithFileUploads;
    public $activeTab = 'crm';
    public $searchCustomer = '';
    public $searchProduct = '';
    public $filterOrderStatus = 'all';
    public $iaDocumentationEnabled = true;
    public $enabledMetrics = [];
    public $clubCologneEnabled = true;

    // Estado del Formulario de Creación de Producto
    public $newName = '';
    public $newPrice = 0;
    public $newDiscount = 0;
    public $newDescription = '';
    public $newFamily = 'Amaderado';
    public $newWood = 50;
    public $newCitrus = 30;
    public $newFloral = 20;
    public $newImage;

    // Propiedad para el Plan
    public $currentPlanName = 'CUENTA PREMIUM';

    // Estado de Edición de Producto
    public $editingProductId = null;
    public $editName = '';
    public $editPrice = 0;
    public $editDiscount = 0;
    public $editDescription = '';
    public $editWood = 50;
    public $editCitrus = 30;
    public $editFloral = 20;

    public function mount()
    {
        // Forzar autenticación de admin
        if (!auth()->check() || !auth()->user()->hasRole(['admin', 'superadmin'])) {
            return redirect()->route('login');
        }

        $this->iaDocumentationEnabled = cache('feature_ia_documentation_enabled', true);
        $this->clubCologneEnabled = cache('club_cologne_enabled', true);
        
        $this->enabledMetrics = cache()->get('metrics_config_global', [
            'Ingresos totales' => true,
            'Ticket promedio' => true,
            'Ventas vs mes anterior' => true,
            'Órdenes del día' => true,
            'Órdenes pendientes' => true,
            'Órdenes canceladas' => true,
            'Envíos activos' => true,
            'Nuevos registros' => true,
            'Clientes recurrentes' => true,
            'Gráfico de ventas' => true,
            'Más vendidos' => true,
            'Stock bajo' => true,
            'Más visitados' => true,
        ]);

        $planId = cache('plan_id', '');
        if ($planId === 'toilette') {
            $this->currentPlanName = 'PLAN TOILETTE';
        } elseif ($planId === 'parfum') {
            $this->currentPlanName = 'PLAN PARFUM';
        } elseif ($planId === 'extracto' || $planId === 'premium') {
            $this->currentPlanName = 'PLAN EXTRACTO';
        } elseif (!empty($planId)) {
            $this->currentPlanName = strtoupper($planId);
        } else {
            $this->currentPlanName = 'CUENTA PREMIUM';
        }
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    // --- ACCIONES DE CLIENTES / CRM ---
    public function getCustomersProperty()
    {
        $query = User::query();

        if (!empty($this->searchCustomer)) {
            $query->where('name', 'like', '%' . $this->searchCustomer . '%')
                  ->orWhere('email', 'like', '%' . $this->searchCustomer . '%');
        }

        return $query->withCount('orders')->withSum('orders', 'total')->get();
    }

    public function getScentInsightsProperty()
    {
        $customers = User::all();
        $wood = 0;
        $citrus = 0;
        $floral = 0;
        $total = 0;

        foreach ($customers as $c) {
            // Simplificado: En un sistema real, el perfil vendría de un test o de sus compras
            // Por ahora simulamos basado en ID para no romper la UI
            $profile = $c->id % 3 == 0 ? 'Amaderado' : ($c->id % 2 == 0 ? 'Cítrico' : 'Floral');
            $total++;
            if ($profile === 'Amaderado') {
                $wood++;
            } elseif ($profile === 'Cítrico') {
                $citrus++;
            } else {
                $floral++;
            }
        }

        if ($total === 0) return ['wood' => 33, 'citrus' => 33, 'floral' => 34, 'total' => 0];

        return [
            'wood' => round(($wood / $total) * 100),
            'citrus' => round(($citrus / $total) * 100),
            'floral' => round(($floral / $total) * 100),
            'total' => $total
        ];
    }

    // --- ACCIONES DE PRODUCTOS ---
    public function getProductsProperty()
    {
        $query = Product::with('category');

        if (!empty($this->searchProduct)) {
            $query->where('name', 'like', '%' . $this->searchProduct . '%');
        }

        return $query->get();
    }

    public function toggleStock($productId)
    {
        $product = Product::find($productId);
        if ($product) {
            $product->stock = $product->stock > 0 ? 0 : 10; // Simple toggle
            $product->save();
            session()->flash('product_success', 'El estado del stock se actualizó correctamente.');
        }
    }

    public function addProduct()
    {
        $this->validate([
            'newName' => 'required|min:3',
            'newPrice' => 'required|numeric|min:1',
            'newDiscount' => 'nullable|numeric|min:0',
            'newDescription' => 'required',
            'newImage' => 'nullable|image|max:4096', // 4MB Max
        ]);

        $category = Category::where('name', $this->newFamily)->first();
        if (!$category) {
            $category = Category::first();
        }

        $imagePath = 'https://lh3.googleusercontent.com/aida-public/AB6AXuDkd86VqaTTXdLZbhBi6DX0-QkTt7recLHsKpzhyvRu6NDRINeZ78Z5LfjpbWEy77zHGNTtim-InM59yDZOLxUMHGv_P_7Ekk1Lr0d8ClDH0BNvnB2QlIKX30wOQc3OZW7hSl0e5k7xb97Rsjg2WoVRqiLwoh9lFSelhOi0jP3gUPYTIZ2pcLlZ90K9bL5dwGf3mXYItL4ZznxilvdyNLkcYfyWVqGQskFj82dNd4cqyDiSRUkYmZVnPeqQwHMywDf78pFWMINP15o';
        
        if ($this->newImage) {
            $imagePath = $this->newImage->store('products', 'public');
        }

        Product::create([
            'name' => $this->newName,
            'description' => $this->newDescription,
            'price' => $this->newPrice,
            'discount' => (int)$this->newDiscount,
            'image' => $imagePath,
            'category_id' => $category->id,
            'wood' => (int)$this->newWood,
            'citrus' => (int)$this->newCitrus,
            'floral' => (int)$this->newFloral,
            'stock' => 10
        ]);

        // Reset fields
        $this->newName = '';
        $this->newPrice = 0;
        $this->newDiscount = 0;
        $this->newDescription = '';
        $this->newWood = 50;
        $this->newCitrus = 30;
        $this->newFloral = 20;
        $this->newImage = null;

        session()->flash('product_success', 'Producto agregado con éxito al catálogo.');
    }

    public function startEdit($productId)
    {
        $product = Product::find($productId);
        if ($product) {
            $this->editingProductId = $productId;
            $this->editName = $product->name;
            $this->editPrice = $product->price;
            $this->editDiscount = $product->discount ?? 0;
            $this->editDescription = $product->description;
            $this->editWood = $product->wood;
            $this->editCitrus = $product->citrus;
            $this->editFloral = $product->floral;
        }
    }

    public function saveEdit()
    {
        $this->validate([
            'editName' => 'required|min:3',
            'editPrice' => 'required|numeric|min:1',
            'editDiscount' => 'nullable|numeric|min:0',
            'editDescription' => 'required',
        ]);

        $product = Product::find($this->editingProductId);
        if ($product) {
            $product->update([
                'name' => $this->editName,
                'price' => $this->editPrice,
                'discount' => (int)$this->editDiscount,
                'description' => $this->editDescription,
                'wood' => (int)$this->editWood,
                'citrus' => (int)$this->editCitrus,
                'floral' => (int)$this->editFloral,
            ]);
            
            $this->editingProductId = null;
            session()->flash('product_success', 'Producto editado con éxito.');
        }
    }

    public function cancelEdit()
    {
        $this->editingProductId = null;
    }

    public function updateProductDiscount($productId, $discount)
    {
        $product = Product::find($productId);
        if ($product) {
            $product->discount = (int)$discount;
            $product->save();
            session()->flash('product_success', 'Descuento actualizado.');
        }
    }

    // --- ACCIONES DE PEDIDOS ---
    public function getOrdersProperty()
    {
        $query = Order::with(['user', 'items']);
        
        if ($this->filterOrderStatus !== 'all') {
            $query->where('status', $this->filterOrderStatus);
        }

        return $query->get();
    }

    public function updateOrderStatus($orderId, $newStatus)
    {
        $order = Order::find($orderId);
        if ($order) {
            $order->status = $newStatus;
            $order->save();
            session()->flash('order_success', "Pedido {$order->order_number} actualizado a {$newStatus}.");
        }
    }

    public function toggleClubCologne()
    {
        $this->clubCologneEnabled = !$this->clubCologneEnabled;
        cache(['club_cologne_enabled' => $this->clubCologneEnabled]);
        $status = $this->clubCologneEnabled ? 'habilitado' : 'deshabilitado';
        session()->flash('message', "El Club de Cologne ha sido {$status}.");
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
