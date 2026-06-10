<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\User;

#[Title('Panel de Super Admin')]
class Dashboard extends Component
{
    use WithFileUploads;

    public $activeTab = 'modulos';
    public $selectedTenant = '';

    // Users Tab Properties
    public $searchUser = '';
    public $roleFilter = '';
    
    // User CRUD Properties
    public $isUserModalOpen = false;
    public $editingUserId = null;
    public $userName = '';
    public $userEmail = '';
    public $userPassword = '';
    public $userRole = 'customer';

    // Store Configuration Properties
    public $storeName = 'Shoply Demo';
    public $storeCurrency = 'ARS';
    public $storeStatus = 'active';
    public $maintenanceStart = '';
    public $maintenanceEnd = '';
    public $whatsapp = '';
    public $instagram = '';
    public $supportEmail = '';
    public $storeLogo;

    // Limits & Billing Properties
    public $limitProducts = 100;
    public $limitUsers = 10;
    public $limitOrders = 500;
    public $planPrice = 0;
    public $planDueDate = '';
    public $planBillingCycle = 'mensual';

    public function mount()
    {
        // Forzar autenticación de superadmin
        if (!auth()->check() || !auth()->user()->hasRole('superadmin')) {
            abort(403, 'Acceso denegado. Se requiere rol de Super Admin.');
        }

        // Cargar configuración de la tienda
        $this->storeName = cache('store_name', 'Esencia');
        $this->storeCurrency = cache('store_currency', 'ARS');
        $this->storeStatus = cache('store_status', 'active');
        $this->whatsapp = cache('store_whatsapp', '+54911223344');
        $this->instagram = cache('store_instagram', 'esencia.latam');
        $this->supportEmail = cache('store_email', 'soporte@esencia.com');

        // Cargar configuración de límites
        $this->limitProducts = cache('limit_products', 100);
        $this->limitUsers = cache('limit_users', 10);
        $this->limitOrders = cache('limit_orders', 500);
        $this->planPrice = cache('plan_price', 0);
        $this->planDueDate = cache('plan_due_date', '');
        $this->planBillingCycle = cache('plan_billing_cycle', 'mensual');
    }

    public function getTenantsProperty()
    {
        // Obtener todos los usuarios, idealmente filtraríamos por rol admin o tenant
        // Pero para asegurar que no esté vacío en esta demo, traemos todos
        return User::all();
    }

    public function getUsersProperty()
    {
        $query = User::query();

        if ($this->searchUser) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->searchUser . '%')
                  ->orWhere('email', 'like', '%' . $this->searchUser . '%');
            });
        }

        if ($this->roleFilter) {
            $query->role($this->roleFilter);
        }

        return $query->get();
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function actionClicked($action)
    {
        session()->flash('message', "Acción ejecutada: $action");
    }

    // --- User CRUD Methods ---

    public function openUserModal()
    {
        $this->resetUserForm();
        $this->isUserModalOpen = true;
    }

    public function closeUserModal()
    {
        $this->isUserModalOpen = false;
        $this->resetUserForm();
    }

    public function resetUserForm()
    {
        $this->reset(['editingUserId', 'userName', 'userEmail', 'userPassword', 'userRole']);
        $this->resetValidation();
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        
        $this->editingUserId = $user->id;
        $this->userName = $user->name;
        $this->userEmail = $user->email;
        $this->userPassword = ''; // Password is empty on edit
        
        $roles = $user->getRoleNames();
        $this->userRole = $roles->first() ?? 'customer';
        
        $this->isUserModalOpen = true;
    }

    public function saveUser()
    {
        $rules = [
            'userName' => 'required|string|max:255',
            'userEmail' => 'required|email|max:255|unique:users,email' . ($this->editingUserId ? ',' . $this->editingUserId : ''),
            'userRole' => 'required|string|in:superadmin,admin,customer',
        ];

        if (!$this->editingUserId || !empty($this->userPassword)) {
            $rules['userPassword'] = 'required|min:8';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->userName,
            'email' => $this->userEmail,
        ];

        if (!empty($this->userPassword)) {
            $data['password'] = bcrypt($this->userPassword);
        }

        if ($this->editingUserId) {
            $user = User::findOrFail($this->editingUserId);
            $user->update($data);
            $user->syncRoles([$this->userRole]);
            $this->logActivity('USER UPDATE', "Usuario actualizado: {$user->email}");
            session()->flash('message', 'Usuario actualizado correctamente.');
        } else {
            $user = User::create($data);
            $user->assignRole($this->userRole);
            $this->logActivity('USER CREATE', "Usuario creado: {$user->email}");
            session()->flash('message', 'Usuario creado correctamente.');
        }

        $this->closeUserModal();
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        // No permitir borrar a ti mismo ni a superadmins
        if ($user->id === auth()->id() || $user->hasRole('superadmin')) {
            session()->flash('error', 'No se puede eliminar este usuario.');
            return;
        }

        $email = $user->email;
        $user->delete();
        
        $this->logActivity('USER DELETE', "Usuario eliminado: {$email}");
        session()->flash('message', 'Usuario eliminado correctamente.');
    }

    public function saveStoreConfig()
    {
        $this->validate([
            'storeLogo' => 'nullable|image|max:2048', // 2MB Max
        ]);

        // Guardar la configuración en caché (como simulación de base de datos para la tienda actual)
        cache(['store_name' => $this->storeName]);
        cache(['store_currency' => $this->storeCurrency]);
        cache(['store_status' => $this->storeStatus]);
        cache(['store_whatsapp' => $this->whatsapp]);
        cache(['store_instagram' => $this->instagram]);
        cache(['store_email' => $this->supportEmail]);

        // Si subieron un logo, lo guardamos
        if ($this->storeLogo) {
            $path = $this->storeLogo->store('logos', 'public');
            cache(['store_logo_path' => $path]);
        }

        $this->logActivity('STORE CONFIG', 'Configuración general de la tienda actualizada.');

        session()->flash('message', 'Configuración de la tienda guardada exitosamente.');
    }

    public function saveLimitsConfig()
    {
        $this->validate([
            'limitProducts' => 'required|numeric|min:0',
            'limitUsers' => 'required|numeric|min:0',
            'limitOrders' => 'required|numeric|min:0',
            'planPrice' => 'required|numeric|min:0',
        ]);

        cache(['limit_products' => $this->limitProducts]);
        cache(['limit_users' => $this->limitUsers]);
        cache(['limit_orders' => $this->limitOrders]);
        cache(['plan_price' => $this->planPrice]);
        cache(['plan_due_date' => $this->planDueDate]);
        cache(['plan_billing_cycle' => $this->planBillingCycle]);

        $this->logActivity('LIMITS UPDATE', 'Límites de recursos y facturación actualizados.');

        session()->flash('message', 'Límites y configuración de facturación guardados exitosamente.');
    }

    public function getAuditLogsProperty()
    {
        $logs = cache('audit_logs', []);
        
        // Seed initial mock data if empty to match design request
        if (empty($logs)) {
            $logs = [
                [
                    'date' => '10/06/2026 16:23:39',
                    'actor' => 'Katrix Super Admin',
                    'email' => 'katrixdevs@gmail.com',
                    'action' => 'CATEGORY METRICS TOGGLE',
                    'description' => 'Todas las métricas de "VENTAS" fueron habilitadas para el tenant.',
                    'details' => '-'
                ],
                [
                    'date' => '10/06/2026 16:23:37',
                    'actor' => 'Katrix Super Admin',
                    'email' => 'katrixdevs@gmail.com',
                    'action' => 'CATEGORY METRICS TOGGLE',
                    'description' => 'Todas las métricas de "VENTAS" fueron deshabilitadas para el tenant.',
                    'details' => '-'
                ],
                [
                    'date' => '10/06/2026 16:20:29',
                    'actor' => 'Katrix Super Admin',
                    'email' => 'katrixdevs@gmail.com',
                    'action' => 'MODULE TOGGLE',
                    'description' => 'Módulo "Chatbot IA" habilitado para el tenant.',
                    'details' => '-'
                ],
                [
                    'date' => '10/06/2026 16:20:25',
                    'actor' => 'Katrix Super Admin',
                    'email' => 'katrixdevs@gmail.com',
                    'action' => 'MODULE TOGGLE',
                    'description' => 'Módulo "Chatbot IA" deshabilitado para el tenant.',
                    'details' => '-'
                ],
            ];
            cache(['audit_logs' => $logs]);
        }
        
        return $logs;
    }

    protected function logActivity($action, $description)
    {
        $logs = cache('audit_logs', []);
        $user = auth()->user();
        
        array_unshift($logs, [
            'date' => now()->format('d/m/Y H:i:s'),
            'actor' => $user ? $user->name : 'Sistema',
            'email' => $user ? $user->email : 'sistema@esencia.com',
            'action' => $action,
            'description' => $description,
            'details' => '-'
        ]);
        
        // Keep only last 50 records
        $logs = array_slice($logs, 0, 50);
        cache(['audit_logs' => $logs]);
    }

    public function render()
    {
        return view('livewire.super-admin.dashboard');
    }
}
