<?php

namespace App\Livewire\Client;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Mi Cuenta - Esencia')]
class Portal extends Component
{
    public $activeSection = 'profile_dna'; // fidelity, profile_dna, orders, settings
    
    // User profile edit fields
    public $name;
    public $email;
    public $phone;
    public $location;
    public $postal_code;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->location = $user->location;
        $this->postal_code = $user->postal_code;

        $this->initializeClientData();
    }

    private function initializeClientData()
    {
        // Enforce mock order history for current logged-in client if none exists
        $orders = session()->get('admin_orders', []);
        $user = auth()->user();

        // Check if there are any orders for this customer
        $hasOrders = false;
        foreach ($orders as $o) {
            if ($o['customer'] === $user->name) {
                $hasOrders = true;
                break;
            }
        }

        if (!$hasOrders) {
            $orders[] = [
                'id' => 'ESP-7811',
                'customer' => $user->name,
                'items' => '1x Santal Raíz (10ml), 1x Discovery Set',
                'total' => 67000,
                'status' => 'Enviado',
                'date' => '2026-05-24',
            ];
            $orders[] = [
                'id' => 'ESP-7940',
                'customer' => $user->name,
                'items' => '1x Brisa de Neroli (5ml)',
                'total' => 14000,
                'status' => 'Pagado',
                'date' => '2026-06-02',
            ];
            session()->put('admin_orders', $orders);
        }

        // Initialize user semillas in session if not set
        if (!session()->has("semillas_{$user->id}")) {
            session()->put("semillas_{$user->id}", 570); // Silver level
        }

        // Initialize user olfactory profile in session if not set
        if (!session()->has('perfil_olfativo')) {
            session()->put('perfil_olfativo', [
                'wood' => 50,
                'citrus' => 30,
                'floral' => 20,
                'label' => 'Amaderado Elegante',
                'description' => 'Tienes preferencia por las notas secas, resinosas y frescas que evocan paseos por bosques y campos cítricos al atardecer.'
            ]);
        }
    }

    public function getSemillasProperty()
    {
        $user = auth()->user();
        return session()->get("semillas_{$user->id}", 570);
    }

    public function getFidelityLevelProperty()
    {
        $points = $this->semillas;
        if ($points < 300) {
            return [
                'name' => 'Bronce',
                'next' => 'Plata',
                'progress' => ($points / 300) * 100,
                'points_needed' => 300 - $points,
                'perks' => ['Envío gratis en compras mayores a $50.000', '1x decant de muestra gratis por año'],
                'color' => 'from-amber-600 to-amber-800'
            ];
        } elseif ($points < 800) {
            return [
                'name' => 'Plata',
                'next' => 'Oro',
                'progress' => (($points - 300) / 500) * 100,
                'points_needed' => 800 - $points,
                'perks' => ['Envío gratis en todas las compras sin mínimo', 'Acceso anticipado a lanzamientos exclusivos', '10% de descuento en el mes de tu cumpleaños'],
                'color' => 'from-slate-400 to-slate-600'
            ];
        } else {
            return [
                'name' => 'Oro',
                'next' => 'Máximo',
                'progress' => 100,
                'points_needed' => 0,
                'perks' => ['Envío gratis express prioritario', 'Asesoría olfativa personalizada trimestral vía Zoom', 'Muestras gratis ilimitadas de cada nuevo pack lanzado', 'Línea de soporte VIP directa'],
                'color' => 'from-yellow-500 to-yellow-600'
            ];
        }
    }

    public function getClientOrdersProperty()
    {
        $orders = session()->get('admin_orders', []);
        $userName = auth()->user()->name;

        return array_filter($orders, function ($order) use ($userName) {
            return $order['customer'] === $userName;
        });
    }

    public function getOlfactiveProfileProperty()
    {
        return session()->get('perfil_olfativo');
    }

    public function getRecommendationsProperty()
    {
        $profile = $this->olfactiveProfile;
        $products = session()->get('admin_products', []);

        // Filter products matching user olfactory dominance
        return array_filter($products, function ($product) use ($profile) {
            if (str_contains(strtolower($profile['label']), 'amaderado')) {
                return $product['family'] === 'Amaderado';
            } elseif (str_contains(strtolower($profile['label']), 'cítrico')) {
                return $product['family'] === 'Cítrico';
            } else {
                return $product['family'] === 'Oriental' || $product['family'] === 'Floral';
            }
        });
    }

    public function getAiRecommendationProperty()
    {
        $recs = $this->recommendations;
        if (empty($recs)) return null;
        
        $top = reset($recs); // get first
        $profile = $this->olfactiveProfile;
        
        $reason = "Basado en tu preferencia por " . $profile['label'] . " (" . $profile['wood'] . "%) y notas secundarias.";
        
        return [
            'product' => $top,
            'reason' => $reason,
            'probability' => 'Alta'
        ];
    }

    public function getTimelineProperty()
    {
        $orders = $this->clientOrders;
        $timeline = [];
        
        foreach($orders as $o) {
            $dateFormatted = date('M d, Y', strtotime($o['date']));
            $amount = isset($o['total']) ? '€' . round($o['total'] / 1000) : '€0'; // Fake euro for matching image
            
            // Extract first item name
            $itemName = 'Productos';
            if (isset($o['items'])) {
                $parts = explode(',', $o['items']);
                $itemName = trim(preg_replace('/^\d+x\s/', '', $parts[0]));
            }
            
            $timeline[] = [
                'date' => $dateFormatted,
                'raw_date' => $o['date'],
                'type' => 'Compra',
                'title' => $itemName,
                'amount' => $amount,
                'color' => 'bg-[#4a7c59]'
            ];
        }
        
        // Add a fake "Consulta"
        $timeline[] = [
            'date' => date('M d, Y', strtotime('-1 month')),
            'raw_date' => date('Y-m-d', strtotime('-1 month')),
            'type' => 'Consulta',
            'title' => 'Test de ADN Olfativo',
            'amount' => '',
            'color' => 'bg-[#dcc48e]'
        ];
        
        // Sort by date desc
        usort($timeline, function($a, $b) {
            return strtotime($b['raw_date']) - strtotime($a['raw_date']);
        });
        
        // Retornar solo las 3 actividades más recientes para el dashboard
        return array_slice($timeline, 0, 3);
    }

    public function getBadgesProperty()
    {
        $profile = $this->olfactiveProfile;
        $level = $this->fidelityLevel['name'];
        return [
            $profile['label'],
            'Premium',
            $level === 'Oro' ? 'VIP' : 'Leal'
        ];
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
        ]);

        $user = auth()->user();
        
        // Update model
        $user->name = $this->name;
        $user->email = $this->email;
        $user->phone = $this->phone;
        $user->location = $this->location;
        $user->postal_code = $this->postal_code;
        $user->save();

        session()->flash('profile_success', 'Tus datos de cuenta fueron actualizados con éxito.');
    }

    public function switchSection($section)
    {
        $this->activeSection = $section;
    }

    public function render()
    {
        return view('livewire.client.portal');
    }
}
