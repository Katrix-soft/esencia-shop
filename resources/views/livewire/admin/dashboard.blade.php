<div x-data="{ sidebarOpen: false }" class="flex bg-[#f4f2ec] font-body relative min-h-screen">
    <!-- Overlay para móvil -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 bg-black/50 z-20 md:hidden" style="display: none;"></div>

    <!-- Sidebar -->
    <aside class="fixed md:sticky top-0 left-0 h-screen w-64 bg-[#14231A] text-white flex flex-col transition-transform duration-300 shadow-xl z-30 md:translate-x-0" :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
        <!-- Logo -->
        <div class="p-8 pb-6 flex flex-col items-center justify-center text-center relative">
            <button @click="sidebarOpen = false" class="absolute top-4 right-4 text-white/50 hover:text-white md:hidden">
                <span class="material-symbols-outlined">close</span>
            </button>
            <h2 class="text-[26px] font-display font-bold tracking-[0.15em] text-[#dcc48e]">ESENCIA</h2>
            <p class="text-[9px] tracking-[0.3em] text-[#dcc48e]/60 uppercase mt-1">Parfumerie</p>
        </div>

        <!-- Usuario / Plan (Movido arriba) -->
        <div class="px-6 py-4 border-y border-white/10 mb-2 bg-white/5">
            <p class="text-[10px] text-white/40 mb-3 tracking-widest uppercase text-center">{{ $currentPlanName ?? 'CUENTA PREMIUM' }}</p>
            <div class="flex items-center gap-3 justify-center">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=4a7c59&color=fff&rounded=true" class="w-10 h-10 shadow-lg rounded-full">
                <div class="text-left w-full overflow-hidden">
                    <p class="text-[13px] font-bold text-white leading-tight truncate" title="{{ auth()->user()->name ?? 'Administrador' }}">{{ auth()->user()->name ?? 'Administrador' }}</p>
                    <p class="text-[10px] text-[#dcc48e] uppercase tracking-wider">
                        @php
                            $roleName = 'Admin';
                            if (auth()->check() && auth()->user()->roles && auth()->user()->roles->count() > 0) {
                                $roleName = auth()->user()->roles->first()->name;
                            }
                            echo ucfirst($roleName);
                        @endphp
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="flex-1 px-4 py-4 space-y-1.5 overflow-y-auto">
            <p class="px-4 text-[10px] text-white/40 uppercase tracking-widest font-bold mb-3 mt-2">Principal</p>
            <button wire:click="switchTab('crm')" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ $activeTab === 'crm' ? 'bg-[#4a7c59] text-white font-bold shadow-md' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                <span class="material-symbols-outlined text-[20px]" style="{{ $activeTab === 'crm' ? 'font-variation-settings: \'FILL\' 1' : '' }}">home</span>
                <span class="text-[13px]">Dashboard</span>
            </button>
            
            <p class="px-4 text-[10px] text-white/40 uppercase tracking-widest font-bold mb-3 mt-8">Gestión</p>
            <button wire:click="switchTab('products')" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ $activeTab === 'products' ? 'bg-[#4a7c59] text-white font-bold shadow-md' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                <span class="material-symbols-outlined text-[20px]" style="{{ $activeTab === 'products' ? 'font-variation-settings: \'FILL\' 1' : '' }}">inventory_2</span>
                <span class="text-[13px]">Catálogo</span>
            </button>
            <button wire:click="switchTab('orders')" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ $activeTab === 'orders' ? 'bg-[#4a7c59] text-white font-bold shadow-md' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                <span class="material-symbols-outlined text-[20px]" style="{{ $activeTab === 'orders' ? 'font-variation-settings: \'FILL\' 1' : '' }}">receipt_long</span>
                <span class="text-[13px]">Pedidos</span>
            </button>
            @if($hasPromotionsFeature)
            <button wire:click="switchTab('promotions')" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ $activeTab === 'promotions' ? 'bg-[#4a7c59] text-white font-bold shadow-md' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                <span class="material-symbols-outlined text-[20px]" style="{{ $activeTab === 'promotions' ? 'font-variation-settings: \'FILL\' 1' : '' }}">sell</span>
                <span class="text-[13px]">Promociones</span>
            </button>
            @endif
            <button wire:click="switchTab('packs')" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ $activeTab === 'packs' ? 'bg-[#4a7c59] text-white font-bold shadow-md' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                <span class="material-symbols-outlined text-[20px]" style="{{ $activeTab === 'packs' ? 'font-variation-settings: \'FILL\' 1' : '' }}">redeem</span>
                <span class="text-[13px]">Packs & Colecciones</span>
            </button>

            @if(auth()->check() && auth()->user()->hasRole('superadmin'))
            <p class="px-4 text-[10px] text-white/40 uppercase tracking-widest font-bold mb-3 mt-8">Sistema</p>
            <a href="{{ route('superadmin.dashboard') }}" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-all text-white/60 hover:text-white hover:bg-[#d32f2f]/20 hover:text-[#ffcdd2]">
                <span class="material-symbols-outlined text-[20px]">admin_panel_settings</span>
                <span class="text-[13px] font-bold">Panel Super Admin</span>
            </a>
            @endif
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col min-w-0 w-full overflow-x-hidden">
        <!-- Topbar -->
        <header class="bg-[#f4f2ec] py-4 md:py-6 px-4 md:px-8 flex justify-between items-center z-10 sticky top-0">
            <div class="flex items-center gap-3 md:gap-4">
                <button @click="sidebarOpen = true" class="md:hidden text-on-surface-variant hover:text-primary p-2 -ml-2 rounded-lg bg-white shadow-sm border border-outline-variant/10">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <h1 class="text-xl md:text-2xl font-headline font-bold text-on-surface tracking-tight flex items-center gap-2">
                    @if($activeTab === 'crm') Dashboard General
                    @elseif($activeTab === 'products') Gestión de Inventario
                    @elseif($activeTab === 'orders') Estado de Pedidos
                    @elseif($activeTab === 'promotions') Promociones y Fidelidad
                    @elseif($activeTab === 'packs') Packs & Colecciones
                    @endif
                </h1>
                
                @if($activeTab === 'packs')
                <div class="flex items-center gap-2 ml-4 bg-white px-3 py-1.5 rounded-full shadow-sm border border-outline-variant/10 cursor-pointer" wire:click="togglePacksSection">
                    <div class="relative inline-flex items-center h-5 rounded-full w-9 transition-colors focus:outline-none {{ $packsSectionEnabled ? 'bg-[#4a7c59]' : 'bg-outline-variant/40' }}">
                        <span class="inline-block w-3.5 h-3.5 transform bg-white rounded-full transition-transform shadow-sm {{ $packsSectionEnabled ? 'translate-x-4.5 translate-x-[18px]' : 'translate-x-1' }}"></span>
                    </div>
                    <span class="text-[10px] font-bold {{ $packsSectionEnabled ? 'text-[#4a7c59]' : 'text-on-surface-variant' }} uppercase tracking-widest select-none">
                        {{ $packsSectionEnabled ? 'Encendido' : 'Apagado' }}
                    </span>
                </div>
                @endif
            </div>
            
            <div class="flex items-center gap-6">
                <!-- Search -->
                <div class="relative w-72 hidden lg:block shadow-sm rounded-full bg-white border border-outline-variant/20 overflow-hidden">
                    <span class="material-symbols-outlined absolute left-3.5 top-2.5 text-on-surface-variant/50 text-[18px]">search</span>
                    <input type="text" placeholder="Buscar productos, clientes..." class="w-full pl-10 pr-4 py-2.5 bg-transparent text-[13px] font-body focus:outline-none">
                </div>
                <!-- Notifs -->
                <button class="relative text-on-surface-variant hover:text-primary transition-colors flex items-center gap-2 bg-white p-2 rounded-full border border-outline-variant/20 shadow-sm">
                    <span class="material-symbols-outlined text-[20px]">notifications</span>
                    <span class="absolute top-2 right-2.5 w-2 h-2 bg-error rounded-full border border-white"></span>
                </button>
            </div>
        </header>

        <!-- Scrollable content area -->
        <div class="flex-1 px-4 md:px-8 pb-12 overflow-x-hidden">
            
            <!-- Tab 1: CRM & Insights -->
            @if($activeTab === 'crm')
            <div class="animate-fade-in space-y-6">
                


                <!-- Unified Analytics & CRM Card -->
                <div class="bg-white rounded-[20px] p-6 shadow-[0_2px_15px_rgba(46,50,48,0.04)]">
                    <div class="flex justify-between items-start mb-6 pb-4 border-b border-outline-variant/10">
                        <div>
                            <h3 class="text-[17px] font-bold font-headline text-on-surface tracking-tight">CRM y Perfiles Olfativos</h3>
                            <p class="text-[12px] text-on-surface-variant font-bold mt-1">Gestión de fidelidad y distribución de familias aromáticas IA</p>
                        </div>
                        <!-- Search Table -->
                        <div class="relative w-56">
                            <span class="material-symbols-outlined absolute left-3 top-2 text-on-surface-variant/50 text-[16px]">search</span>
                            <input type="text" wire:model.live="searchCustomer" placeholder="Buscar cliente..." class="w-full pl-9 pr-3 py-1.5 border border-outline-variant/20 bg-[#f4f2ec] rounded-lg text-[12px] font-body focus:outline-none focus:border-primary">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Distribución Perfiles (Izquierda) -->
                        <div class="flex-1 flex flex-col items-center justify-start lg:border-r border-outline-variant/10 lg:pr-8 py-2">
                            @php
                                $insights = $this->scentInsights;
                            @endphp
                            <div class="relative w-44 h-44 flex items-center justify-center">
                                <svg class="w-full h-full -rotate-90" viewBox="0 0 42 42">
                                    <circle cx="21" cy="21" r="15.915" fill="transparent" stroke="#f4f2ec" stroke-width="4"></circle>
                                    <circle cx="21" cy="21" r="15.915" fill="transparent" stroke="#14231A" stroke-width="4" 
                                            stroke-dasharray="{{ $insights['wood'] }} {{ 100 - $insights['wood'] }}" 
                                            stroke-dashoffset="100"></circle>
                                    <circle cx="21" cy="21" r="15.915" fill="transparent" stroke="#4a7c59" stroke-width="4" 
                                            stroke-dasharray="{{ $insights['citrus'] }} {{ 100 - $insights['citrus'] }}" 
                                            stroke-dashoffset="{{ 100 - $insights['wood'] }}"></circle>
                                    <circle cx="21" cy="21" r="15.915" fill="transparent" stroke="#dcc48e" stroke-width="4" 
                                            stroke-dasharray="{{ $insights['floral'] }} {{ 100 - $insights['floral'] }}" 
                                            stroke-dashoffset="{{ 100 - $insights['wood'] - $insights['citrus'] }}"></circle>
                                </svg>
                                <div class="absolute inset-0 flex flex-col items-center justify-center">
                                    <span class="text-[28px] font-headline font-bold text-on-surface leading-none">{{ $insights['total'] }}</span>
                                    <span class="text-[9px] font-bold text-on-surface-variant uppercase mt-1 tracking-widest">Perfiles</span>
                                </div>
                            </div>

                            <div class="w-full mt-8 flex justify-around">
                                <div class="text-center">
                                    <div class="w-3 h-3 rounded-full bg-[#14231A] mx-auto mb-1"></div>
                                    <p class="text-[12px] font-bold text-on-surface leading-tight">{{ $insights['wood'] }}%</p>
                                    <span class="text-[10px] text-on-surface-variant font-bold">Amaderado</span>
                                </div>
                                <div class="text-center">
                                    <div class="w-3 h-3 rounded-full bg-[#4a7c59] mx-auto mb-1"></div>
                                    <p class="text-[12px] font-bold text-on-surface leading-tight">{{ $insights['citrus'] }}%</p>
                                    <span class="text-[10px] text-on-surface-variant font-bold">Cítrico</span>
                                </div>
                                <div class="text-center">
                                    <div class="w-3 h-3 rounded-full bg-[#dcc48e] mx-auto mb-1"></div>
                                    <p class="text-[12px] font-bold text-on-surface leading-tight">{{ $insights['floral'] }}%</p>
                                    <span class="text-[10px] text-on-surface-variant font-bold">Floral</span>
                                </div>
                            </div>
                        </div>

                        <!-- CRM Table (Derecha) -->
                        <div class="lg:col-span-2 overflow-x-auto py-2">
                            <table class="w-full text-left border-collapse min-w-[500px]">
                                <thead>
                                    <tr class="text-[10px] text-on-surface-variant uppercase font-bold tracking-widest border-b border-outline-variant/10">
                                        <th class="py-2 pb-4">Cliente</th>
                                        <th class="py-2 pb-4">Perfil Olfativo</th>
                                        @if($clubCologneEnabled)
                                        <th class="py-2 pb-4 text-center">Puntos</th>
                                        @endif
                                        <th class="py-2 pb-4 text-right">Total Compras</th>
                                    </tr>
                                </thead>
                                <tbody class="text-[13px] font-body">
                                    @forelse($this->customers as $customer)
                                        @php
                                            $profile = $customer->id % 3 == 0 ? 'Amaderado' : ($customer->id % 2 == 0 ? 'Cítrico' : 'Floral');
                                            $badgeClass = $profile === 'Amaderado' ? 'bg-[#14231A]/10 text-[#14231A]' : ($profile === 'Cítrico' ? 'bg-[#4a7c59]/10 text-[#4a7c59]' : 'bg-[#dcc48e]/20 text-[#8a6e30]');
                                        @endphp
                                        <tr class="hover:bg-[#f4f2ec]/50 transition-colors border-b border-outline-variant/5 last:border-0">
                                            <td class="py-3 flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-[#f4f2ec] border border-outline-variant/20 flex items-center justify-center font-bold text-on-surface text-[13px]">
                                                    {{ substr($customer->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="font-bold text-on-surface">{{ $customer->name }}</p>
                                                    <p class="text-on-surface-variant text-[11px]">{{ $customer->email }}</p>
                                                </div>
                                            </td>
                                            <td class="py-3">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold {{ $badgeClass }}">
                                                    {{ $profile }}
                                                </span>
                                            </td>
                                            @if($clubCologneEnabled)
                                            <td class="py-3 text-center">
                                                <span class="font-bold text-on-surface bg-[#f4f2ec] px-2 py-1 rounded-md text-[11px]">{{ $customer->orders_count * 10 }}</span>
                                            </td>
                                            @endif
                                            <td class="py-3 text-right">
                                                <p class="font-bold text-on-surface">${{ number_format($customer->orders_sum_total ?? 0, 0, ',', '.') }}</p>
                                                <p class="text-on-surface-variant text-[10px]">{{ $customer->orders_count }} pedidos</p>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-8 text-center text-on-surface-variant text-[13px]">No se encontraron clientes.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Panel de Ventas Detallado -->
                <div class="bg-white rounded-[20px] p-8 shadow-[0_2px_15px_rgba(46,50,48,0.04)] min-h-[600px] mt-6">
                    @include('livewire.metrics-dashboard')
                </div>
            </div>
            @endif

            <!-- Tab 2: Catálogo de Productos -->
            @if($activeTab === 'products')
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 animate-fade-in">
                    <!-- Product Alert Feedback -->
                    @if (session()->has('product_success'))
                        <div class="lg:col-span-12 p-4 bg-[#4a7c59]/10 text-[#4a7c59] rounded-xl border border-[#4a7c59]/20 flex items-start gap-3 shadow-sm font-body">
                            <span class="material-symbols-outlined mt-0.5" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                            <div>
                                <h4 class="font-bold text-[13px]">Operación Exitosa</h4>
                                <p class="text-[12px]">{{ session('product_success') }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Left: Add/Edit Product Panel -->
                    <div class="lg:col-span-4 bg-white rounded-[20px] p-6 shadow-[0_2px_15px_rgba(46,50,48,0.04)] h-fit">
                        @if($editingProductId)
                            <h3 class="text-[17px] font-bold font-headline mb-6 text-primary flex items-center gap-2">
                                <span class="material-symbols-outlined text-[20px]">edit</span> Editar Producto
                            </h3>
                            <!-- Formulario de Edición (Igual a tu original pero ajustado) -->
                            <form wire:submit.prevent="saveEdit" class="space-y-4">
                                <div>
                                    <label class="text-[12px] font-bold text-on-surface-variant mb-1 block">Nombre del Perfume</label>
                                    <input type="text" wire:model="editName" class="w-full px-4 py-2 border border-outline-variant/30 bg-[#f4f2ec] rounded-xl text-[13px] focus:outline-none focus:border-primary" required>
                                    @error('editName') <span class="text-error text-[11px] block mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="text-[12px] font-bold text-on-surface-variant mb-1 block">Precio ($ ARS)</label>
                                    <input type="number" wire:model="editPrice" class="w-full px-4 py-2 border border-outline-variant/30 bg-[#f4f2ec] rounded-xl text-[13px] focus:outline-none focus:border-primary" required>
                                </div>
                                <div>
                                    <label class="text-[12px] font-bold text-on-surface-variant mb-1 block">Descripción</label>
                                    <textarea wire:model="editDescription" rows="3" class="w-full px-4 py-2 border border-outline-variant/30 bg-[#f4f2ec] rounded-xl text-[13px] focus:outline-none focus:border-primary" required></textarea>
                                </div>
                                <div>
                                    <label class="text-[12px] font-bold text-on-surface-variant mb-1 block">Descuento (%) - Opcional</label>
                                    <input type="number" wire:model="editDiscount" min="0" max="100" class="w-full px-4 py-2 border border-outline-variant/30 bg-[#f4f2ec] rounded-xl text-[13px] focus:outline-none focus:border-primary">
                                </div>
                                <div class="flex gap-3 pt-2">
                                    <button type="button" wire:click="cancelEdit" class="flex-1 py-2.5 border border-outline-variant rounded-full text-[12px] font-bold text-on-surface-variant">Cancelar</button>
                                    <button type="submit" class="flex-1 py-2.5 bg-primary text-white rounded-full text-[12px] font-bold shadow-md">Guardar</button>
                                </div>
                            </form>
                        @else
                            <h3 class="text-[17px] font-bold font-headline mb-6 flex items-center gap-2 text-on-surface">
                                <span class="material-symbols-outlined text-[20px]">add_circle</span> Añadir Nuevo
                            </h3>

                            <!-- Botón de carga rápida PDF -->
                            @if(isset($iaDocumentationEnabled) && $iaDocumentationEnabled)
                            <div class="mb-6 p-4 rounded-xl border-2 border-dashed border-[#4a7c59]/30 bg-[#4a7c59]/5 flex flex-col items-center justify-center gap-1.5 text-center transition-colors hover:bg-[#4a7c59]/10 cursor-pointer" onclick="alert('Función de extracción de PDF en desarrollo')">
                                <span class="material-symbols-outlined text-primary text-[28px]">picture_as_pdf</span>
                                <span class="text-[13px] font-bold text-primary font-headline">Cargar documentación</span>
                                <span class="text-[11px] text-on-surface-variant font-body px-2">Sube un PDF para autocompletar rápidamente.</span>
                            </div>
                            @endif

                            <form wire:submit.prevent="addProduct" class="space-y-4">
                                <div>
                                    <label class="text-[12px] font-bold text-on-surface-variant mb-1 block">Nombre</label>
                                    <input type="text" wire:model="newName" class="w-full px-4 py-2 border border-outline-variant/30 bg-[#f4f2ec] rounded-xl text-[13px] focus:outline-none focus:border-primary" required>
                                    @error('newName') <span class="text-error text-[11px] block mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="text-[12px] font-bold text-on-surface-variant mb-1 block">Precio ($ ARS)</label>
                                    <input type="number" wire:model="newPrice" class="w-full px-4 py-2 border border-outline-variant/30 bg-[#f4f2ec] rounded-xl text-[13px] focus:outline-none focus:border-primary" required>
                                </div>
                                <div>
                                    <label class="text-[12px] font-bold text-on-surface-variant mb-1 block">Descripción</label>
                                    <textarea wire:model="newDescription" rows="3" class="w-full px-4 py-2 border border-outline-variant/30 bg-[#f4f2ec] rounded-xl text-[13px] focus:outline-none focus:border-primary" required></textarea>
                                </div>
                                <div>
                                    <label class="text-[12px] font-bold text-on-surface-variant mb-1 block">Descuento (%) - Opcional</label>
                                    <input type="number" wire:model="newDiscount" min="0" max="100" class="w-full px-4 py-2 border border-outline-variant/30 bg-[#f4f2ec] rounded-xl text-[13px] focus:outline-none focus:border-primary">
                                </div>
                                
                                <div>
                                    <label class="text-[12px] font-bold text-on-surface-variant mb-1 block">Foto del Producto</label>
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-outline-variant/30 border-dashed rounded-xl bg-[#f4f2ec] relative">
                                        <div class="space-y-1 text-center">
                                            @if ($newImage)
                                                <img src="{{ $newImage->temporaryUrl() }}" class="mx-auto h-24 w-24 object-cover rounded-md mb-2">
                                            @else
                                                <span class="material-symbols-outlined text-outline-variant/50 text-[32px]">image</span>
                                            @endif
                                            <div class="flex text-[12px] text-on-surface-variant justify-center">
                                                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-bold text-primary hover:text-primary/80 focus-within:outline-none px-2 py-0.5 border border-outline-variant/20 shadow-sm">
                                                    <span>Subir imagen</span>
                                                    <input id="file-upload" type="file" wire:model="newImage" class="sr-only" accept="image/*">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @error('newImage') <span class="text-error text-[11px] block mt-1">{{ $message }}</span> @enderror
                                </div>

                                <button type="submit" class="w-full mt-2 py-2.5 bg-primary text-white rounded-full text-[13px] font-bold shadow-md">Crear Producto</button>
                            </form>
                        @endif
                    </div>

                    <!-- Right: Products List -->
                    <div class="lg:col-span-8 bg-white rounded-[20px] p-6 shadow-[0_2px_15px_rgba(46,50,48,0.04)]">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-[17px] font-bold font-headline text-on-surface tracking-tight">Perfumes en Catálogo</h3>
                            <div class="relative w-56">
                                <span class="material-symbols-outlined absolute left-3 top-2 text-on-surface-variant/50 text-[16px]">search</span>
                                <input type="text" wire:model.live="searchProduct" placeholder="Buscar perfume..." class="w-full pl-9 pr-3 py-1.5 border border-outline-variant/20 bg-[#f4f2ec] rounded-lg text-[12px] font-body focus:outline-none focus:border-primary">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4">
                            @forelse($this->products as $p)
                                <div class="bg-white border border-outline-variant/20 rounded-[12px] p-4 flex justify-between items-center shadow-sm">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-lg bg-[#f4f2ec] border border-outline-variant/10 flex items-center justify-center overflow-hidden">
                                            @if($p->image)
                                                <img src="{{ str_contains($p->image, 'http') ? $p->image : asset('storage/'.$p->image) }}" class="w-full h-full object-cover">
                                            @else
                                                <span class="material-symbols-outlined text-outline-variant text-[20px]">inventory_2</span>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-[14px] font-headline text-on-surface">{{ $p->name }}</h4>
                                            <p class="text-[11px] text-on-surface-variant max-w-sm truncate">{{ $p->description }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-6">
                                        <!-- Cuadro de Descuento Rápido -->
                                        <div class="flex items-center gap-1.5 bg-[#f4f2ec] px-2 py-1.5 rounded-md border border-outline-variant/20" x-data="{ discountVal: {{ $p->discount ?? 0 }}, applying: false }">
                                            <span class="text-[10px] font-bold text-on-surface-variant uppercase">Descuento:</span>
                                            <input type="number" min="0" max="100" x-model="discountVal" 
                                                   class="w-10 px-0.5 text-center border-b border-outline-variant/30 text-[12px] font-bold text-primary focus:border-primary focus:outline-none bg-transparent">
                                            <span class="text-[10px] font-bold text-on-surface-variant">%</span>
                                            <button type="button" 
                                                    x-on:click="applying = true; $wire.updateProductDiscount({{ $p->id }}, discountVal).then(() => { setTimeout(() => applying = false, 1500) })" 
                                                    x-text="applying ? '¡Aplicado!' : 'Aplicar'"
                                                    :class="applying ? 'bg-primary hover:bg-primary/90 scale-105' : 'bg-error hover:bg-red-700'"
                                                    class="ml-1 px-2 py-0.5 text-white text-[9px] font-bold rounded transition-all uppercase shadow-sm w-[55px]">
                                                Aplicar
                                            </button>
                                        </div>
                                        
                                        <div class="flex items-center gap-2 text-right">
                                            @if($p->discount > 0)
                                                <span class="bg-[#dcc48e]/20 text-[#8a6e30] px-2 py-0.5 rounded text-[11px] font-bold">{{ $p->discount }}% OFF</span>
                                            @endif
                                            <span class="text-[15px] font-bold text-primary">${{ number_format($p->price, 0, ',', '.') }}</span>
                                        </div>
                                        <button wire:click="startEdit({{ $p->id }})" class="px-3 py-1.5 border border-outline-variant rounded-md text-[11px] font-bold text-on-surface hover:bg-[#f4f2ec]">Editar</button>
                                    </div>
                                </div>
                            @empty
                                <div class="py-12 text-center text-on-surface-variant font-bold text-[13px]">No hay productos.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif

            <!-- Tab 3: Pedidos -->
            @if($activeTab === 'orders')
                <div class="bg-white rounded-[20px] p-8 shadow-[0_2px_15px_rgba(46,50,48,0.04)] animate-fade-in">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-[17px] font-bold font-headline text-on-surface tracking-tight">Registro de Pedidos</h3>
                        <div class="flex bg-[#f4f2ec] p-1 rounded-lg border border-outline-variant/20 text-[11px] font-bold">
                            <button wire:click="$set('filterOrderStatus', 'all')" class="px-3 py-1 rounded-md {{ $filterOrderStatus === 'all' ? 'bg-white shadow-sm text-on-surface' : 'text-on-surface-variant' }}">Todos</button>
                            <button wire:click="$set('filterOrderStatus', 'Pendiente')" class="px-3 py-1 rounded-md {{ $filterOrderStatus === 'Pendiente' ? 'bg-white shadow-sm text-on-surface' : 'text-on-surface-variant' }}">Pendientes</button>
                            <button wire:click="$set('filterOrderStatus', 'Pagado')" class="px-3 py-1 rounded-md {{ $filterOrderStatus === 'Pagado' ? 'bg-white shadow-sm text-on-surface' : 'text-on-surface-variant' }}">Pagados</button>
                            <button wire:click="$set('filterOrderStatus', 'Enviado')" class="px-3 py-1 rounded-md {{ $filterOrderStatus === 'Enviado' ? 'bg-white shadow-sm text-on-surface' : 'text-on-surface-variant' }}">Enviados</button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-[10px] text-on-surface-variant uppercase font-bold tracking-widest border-b border-outline-variant/10">
                                    <th class="py-3 pb-3">Pedido ID</th>
                                    <th class="py-3 pb-3">Cliente</th>
                                    <th class="py-3 pb-3">Items</th>
                                    <th class="py-3 pb-3">Total</th>
                                    <th class="py-3 pb-3">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="text-[13px] font-body">
                                @forelse($this->orders as $o)
                                    <tr class="hover:bg-[#f4f2ec]/50 transition-colors border-b border-outline-variant/5 last:border-0">
                                        <td class="py-4 font-bold text-on-surface">#{{ $o->order_number }}</td>
                                        <td class="py-4 text-on-surface">{{ $o->user ? $o->user->name : 'Invitado' }}</td>
                                        <td class="py-4 text-on-surface-variant text-[12px]">{{ Str::limit($o->items->pluck('product_name')->join(', '), 30) }}</td>
                                        <td class="py-4 font-bold text-primary">${{ number_format($o->total, 0, ',', '.') }}</td>
                                        <td class="py-4">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold {{ $o->status === 'Enviado' ? 'bg-[#1b2b20]/10 text-[#1b2b20]' : ($o->status === 'Pagado' ? 'bg-[#4a7c59]/10 text-[#4a7c59]' : 'bg-error/10 text-error') }}">
                                                {{ $o->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-on-surface-variant text-[13px]">No hay pedidos con este filtro.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Tab 4: Promociones -->
            @if($hasPromotionsFeature)
                @if($activeTab === 'promotions')
                    <div class="bg-white rounded-[20px] p-8 shadow-[0_2px_15px_rgba(46,50,48,0.04)] animate-fade-in text-center py-16">
                        <span class="material-symbols-outlined text-[48px] text-[#4a7c59] mb-4">sell</span>
                        <h3 class="text-2xl font-bold font-headline text-on-surface mb-2">Gestión de Promociones</h3>
                        <p class="text-on-surface-variant text-[13px] font-body mb-8 max-w-md mx-auto">Administra los descuentos y el programa de fidelidad para tus clientes más leales.</p>
                        
                        <button wire:click="toggleClubCologne" class="inline-flex items-center gap-2 px-8 py-3 rounded-full font-bold shadow-md transition-all {{ $clubCologneEnabled ? 'bg-[#4a7c59] hover:bg-[#3d6649] text-white' : 'bg-gray-200 text-gray-500 hover:bg-gray-300' }}">
                            <span class="material-symbols-outlined text-[20px]">{{ $clubCologneEnabled ? 'verified' : 'block' }}</span>
                            {{ $clubCologneEnabled ? 'Club de Cologne (Activo)' : 'Club de Cologne (Inactivo)' }}
                        </button>

                        @if(session()->has('message'))
                            <div class="mt-4 text-sm font-bold {{ $clubCologneEnabled ? 'text-[#4a7c59]' : 'text-gray-500' }}">
                                {{ session('message') }}
                            </div>
                        @endif
                    </div>
                @endif
            @endif

            <!-- Tab 5: Packs y Colecciones -->
            @if($activeTab === 'packs')
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 animate-fade-in">
                    <!-- Feedback Alert -->
                    @if (session()->has('pack_success'))
                        <div class="lg:col-span-12 p-4 bg-[#4a7c59]/10 text-[#4a7c59] rounded-xl border border-[#4a7c59]/20 flex items-start gap-3 shadow-sm font-body">
                            <span class="material-symbols-outlined mt-0.5" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                            <div>
                                <h4 class="font-bold text-[13px]">Operación Exitosa</h4>
                                <p class="text-[12px]">{{ session('pack_success') }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Left: Add Pack Panel -->
                    <div class="lg:col-span-4 bg-white rounded-[20px] p-6 shadow-[0_2px_15px_rgba(46,50,48,0.04)] h-fit">
                        <h3 class="text-[17px] font-bold font-headline mb-6 flex items-center gap-2 text-on-surface">
                            <span class="material-symbols-outlined text-[20px]">library_add</span> Crear Pack
                        </h3>

                        <form wire:submit.prevent="createPack" class="space-y-4">
                            <div>
                                <label class="text-[12px] font-bold text-on-surface-variant mb-1 block">Nombre</label>
                                <input type="text" wire:model="newPackName" class="w-full px-4 py-2 border border-outline-variant/30 bg-[#f4f2ec] rounded-xl text-[13px] focus:outline-none focus:border-primary" required>
                                @error('newPackName') <span class="text-error text-[11px] block mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="text-[12px] font-bold text-on-surface-variant mb-1 block">Precio ($ ARS)</label>
                                <input type="number" wire:model="newPackPrice" class="w-full px-4 py-2 border border-outline-variant/30 bg-[#f4f2ec] rounded-xl text-[13px] focus:outline-none focus:border-primary" required>
                                @error('newPackPrice') <span class="text-error text-[11px] block mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="text-[12px] font-bold text-on-surface-variant mb-1 block">Descripción</label>
                                <textarea wire:model="newPackDescription" rows="3" class="w-full px-4 py-2 border border-outline-variant/30 bg-[#f4f2ec] rounded-xl text-[13px] focus:outline-none focus:border-primary" required></textarea>
                                @error('newPackDescription') <span class="text-error text-[11px] block mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="text-[12px] font-bold text-on-surface-variant mb-1 block">Descuento (%) - Opcional</label>
                                <input type="number" wire:model="newPackDiscount" min="0" max="100" class="w-full px-4 py-2 border border-outline-variant/30 bg-[#f4f2ec] rounded-xl text-[13px] focus:outline-none focus:border-primary">
                            </div>
                            
                            <div>
                                <label class="text-[12px] font-bold text-on-surface-variant mb-1 block">Foto del Pack</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-outline-variant/30 border-dashed rounded-xl bg-[#f4f2ec] relative">
                                    <div class="space-y-1 text-center">
                                        @if ($newPackImage)
                                            <img src="{{ $newPackImage->temporaryUrl() }}" class="mx-auto h-24 w-24 object-cover rounded-md mb-2">
                                        @else
                                            <span class="material-symbols-outlined text-outline-variant/50 text-[32px]">image</span>
                                        @endif
                                        <div class="flex text-[12px] text-on-surface-variant justify-center">
                                            <label for="pack-file-upload" class="relative cursor-pointer bg-white rounded-md font-bold text-primary hover:text-primary/80 focus-within:outline-none px-2 py-0.5 border border-outline-variant/20 shadow-sm">
                                                <span>Subir imagen</span>
                                                <input id="pack-file-upload" type="file" wire:model="newPackImage" class="sr-only" accept="image/*">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @error('newPackImage') <span class="text-error text-[11px] block mt-1">{{ $message }}</span> @enderror
                            </div>
                            
                            @error('selectedPackProducts')
                                <div class="p-3 bg-error/10 text-error rounded-xl border border-error/20 text-[11px] font-bold mt-2">
                                    <span class="material-symbols-outlined text-[14px] align-middle">error</span> {{ $message }}
                                </div>
                            @enderror

                            <button type="submit" class="w-full mt-2 py-2.5 bg-[#4a7c59] text-white rounded-full text-[13px] font-bold shadow-md hover:bg-[#3d6649] transition-colors">Crear Pack</button>
                        </form>
                    </div>

                    <!-- Right: Products Catalog Selection -->
                    <div class="lg:col-span-8 bg-white rounded-[20px] p-6 shadow-[0_2px_15px_rgba(46,50,48,0.04)]">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="text-[17px] font-bold font-headline text-on-surface tracking-tight">Catálogo de Productos</h3>
                                <p class="text-[11px] text-on-surface-variant font-bold mt-1">Selecciona los productos que conformarán este pack</p>
                            </div>
                            <div class="bg-[#f4f2ec] px-3 py-1.5 rounded-lg border border-outline-variant/20 font-bold text-[12px] text-[#4a7c59]">
                                Seleccionados: {{ count($selectedPackProducts) }}
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse($this->products as $p)
                                @php
                                    $isSelected = in_array($p->id, $selectedPackProducts);
                                @endphp
                                <div wire:click="togglePackProduct({{ $p->id }})" class="cursor-pointer border {{ $isSelected ? 'border-[#4a7c59] bg-[#4a7c59]/5' : 'border-outline-variant/20 bg-white hover:bg-[#f4f2ec]' }} rounded-[12px] p-3 flex items-center gap-3 shadow-sm transition-all">
                                    <div class="w-12 h-12 rounded-lg bg-[#f4f2ec] border border-outline-variant/10 flex items-center justify-center overflow-hidden shrink-0">
                                        @if($p->image)
                                            <img src="{{ str_contains($p->image, 'http') ? $p->image : asset('storage/'.$p->image) }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="material-symbols-outlined text-outline-variant text-[20px]">inventory_2</span>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-bold text-[13px] font-headline text-on-surface truncate">{{ $p->name }}</h4>
                                        <p class="text-[10px] text-on-surface-variant truncate">{{ $p->category ? $p->category->name : 'General' }}</p>
                                    </div>
                                    <div class="shrink-0 flex items-center justify-center w-6 h-6 rounded-full border {{ $isSelected ? 'bg-[#4a7c59] border-[#4a7c59] text-white' : 'border-outline-variant text-transparent' }}">
                                        <span class="material-symbols-outlined text-[14px] font-bold" style="font-variation-settings: 'FILL' 1;">check</span>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-1 md:col-span-2 py-12 text-center text-on-surface-variant font-bold text-[13px]">No hay productos en el catálogo.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </main>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.3s ease-out forwards;
    }
    /* Estilos del scroll para el panel derecho */
    ::-webkit-scrollbar {
        width: 6px;
    }
    ::-webkit-scrollbar-track {
        background: transparent;
    }
    ::-webkit-scrollbar-thumb {
        background-color: #d4ccbf;
        border-radius: 10px;
    }
</style>
