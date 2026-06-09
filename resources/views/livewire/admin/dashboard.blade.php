<div class="w-full bg-background min-h-screen py-12 px-6 max-w-7xl mx-auto">
    <!-- Header -->
    <header class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-outline-variant/30 pb-6">
        <div>
            <span class="inline-block py-1 px-4 mb-2 bg-primary/10 text-primary font-bold rounded-full text-xs tracking-wider uppercase font-body">Módulos Internos</span>
            <h1 class="text-4xl font-headline font-bold text-on-surface">Panel de Control & CRM</h1>
            <p class="text-on-surface-variant font-body mt-1">Supervisa las analíticas olfativas, el catálogo de decants y los pedidos de los clientes.</p>
        </div>
        
        <!-- Navigation Tabs -->
        <div class="flex bg-surface-container-low p-1.5 rounded-xl border border-outline-variant/30">
            <button wire:click="switchTab('crm')" class="px-5 py-2.5 rounded-lg text-sm font-bold transition-all font-body flex items-center gap-2 {{ $activeTab === 'crm' ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:text-primary' }}">
                <span class="material-symbols-outlined text-[18px]">group</span>
                CRM & Insights
            </button>
            <button wire:click="switchTab('products')" class="px-5 py-2.5 rounded-lg text-sm font-bold transition-all font-body flex items-center gap-2 {{ $activeTab === 'products' ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:text-primary' }}">
                <span class="material-symbols-outlined text-[18px]">inventory</span>
                Catálogo
            </button>
            <button wire:click="switchTab('orders')" class="px-5 py-2.5 rounded-lg text-sm font-bold transition-all font-body flex items-center gap-2 {{ $activeTab === 'orders' ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:text-primary' }}">
                <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                Pedidos
            </button>
        </div>
    </header>

    <!-- Tab 1: CRM & Scent Insights -->
    @if($activeTab === 'crm')
        <div class="space-y-8 animate-fade-in">
            <!-- Bento Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Stat 1 -->
                <div class="bg-surface-container-high rounded-2xl p-6 border border-outline-variant/20 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-[28px]">payments</span>
                    </div>
                    <div>
                        <span class="text-xs text-on-surface-variant font-body">Ventas Totales</span>
                        <h4 class="text-2xl font-bold font-headline mt-1">$237.000</h4>
                    </div>
                </div>
                <!-- Stat 2 -->
                <div class="bg-surface-container-high rounded-2xl p-6 border border-outline-variant/20 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 bg-tertiary/10 text-tertiary rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-[28px]">shopping_bag</span>
                    </div>
                    <div>
                        <span class="text-xs text-on-surface-variant font-body">Ticket Promedio</span>
                        <h4 class="text-2xl font-bold font-headline mt-1">$49.200</h4>
                    </div>
                </div>
                <!-- Stat 3 -->
                <div class="bg-surface-container-high rounded-2xl p-6 border border-outline-variant/20 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-[28px]">trending_up</span>
                    </div>
                    <div>
                        <span class="text-xs text-on-surface-variant font-body">Conversión Web</span>
                        <h4 class="text-2xl font-bold font-headline mt-1">3.4%</h4>
                    </div>
                </div>
                <!-- Stat 4 -->
                <div class="bg-surface-container-high rounded-2xl p-6 border border-outline-variant/20 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 bg-tertiary-fixed-dim/10 text-tertiary-fixed-dim rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-[28px]">group_add</span>
                    </div>
                    <div>
                        <span class="text-xs text-on-surface-variant font-body">Clientes Activos</span>
                        <h4 class="text-2xl font-bold font-headline mt-1">4</h4>
                    </div>
                </div>
            </div>

            <!-- Scent Insights & Loyalty Summary -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Olfactory Distribution Chart -->
                <div class="bg-surface-container-low border border-outline-variant/20 rounded-2xl p-8 flex flex-col items-center justify-center">
                    <div class="mb-6 w-full flex justify-between items-start">
                        <div class="text-left">
                            <h3 class="text-lg font-bold font-headline">Distribución de Perfiles Olfativos</h3>
                            <p class="text-xs text-on-surface-variant font-body">Familias preferidas de los clientes testeados.</p>
                        </div>
                        <span class="material-symbols-outlined text-primary">auto_awesome</span>
                    </div>
                    
                    <!-- SVG Pie / Donut Chart -->
                    @php
                        $insights = $this->scentInsights;
                        $woodDeg = $insights['wood'] * 3.6;
                        $citrusDeg = $insights['citrus'] * 3.6;
                        $floralDeg = $insights['floral'] * 3.6;
                    @endphp
                    <div class="relative w-48 h-48 flex items-center justify-center my-4">
                        <svg class="w-full h-full -rotate-90" viewBox="0 0 42 42">
                            <!-- Background ring -->
                            <circle cx="21" cy="21" r="15.915" fill="transparent" stroke="#e0e0e0" stroke-width="4"></circle>
                            
                            <!-- Wood Segment -->
                            <circle cx="21" cy="21" r="15.915" fill="transparent" stroke="#4a7c59" stroke-width="4" 
                                    stroke-dasharray="{{ $insights['wood'] }} {{ 100 - $insights['wood'] }}" 
                                    stroke-dashoffset="100"></circle>
                            
                            <!-- Citrus Segment -->
                            <circle cx="21" cy="21" r="15.915" fill="transparent" stroke="#705c30" stroke-width="4" 
                                    stroke-dasharray="{{ $insights['citrus'] }} {{ 100 - $insights['citrus'] }}" 
                                    stroke-dashoffset="{{ 100 - $insights['wood'] }}"></circle>
                            
                            <!-- Floral Segment -->
                            <circle cx="21" cy="21" r="15.915" fill="transparent" stroke="#dcc48e" stroke-width="4" 
                                    stroke-dasharray="{{ $insights['floral'] }} {{ 100 - $insights['floral'] }}" 
                                    stroke-dashoffset="{{ 100 - $insights['wood'] - $insights['citrus'] }}"></circle>
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-3xl font-display font-bold text-primary">{{ count(array_filter(session()->get('admin_customers', []), fn($c) => $c['profile'] !== 'No Calculado')) }}</span>
                            <span class="text-[9px] font-bold text-on-surface-variant tracking-wider uppercase font-body">Perfiles IA</span>
                        </div>
                    </div>

                    <!-- Legend -->
                    <div class="grid grid-cols-3 gap-2 w-full mt-6 text-center">
                        <div>
                            <div class="w-3 h-3 rounded-full bg-primary mx-auto mb-1"></div>
                            <span class="text-[10px] text-on-surface-variant font-body">Amaderado</span>
                            <p class="text-xs font-bold text-primary">{{ $insights['wood'] }}%</p>
                        </div>
                        <div>
                            <div class="w-3 h-3 rounded-full bg-tertiary mx-auto mb-1"></div>
                            <span class="text-[10px] text-on-surface-variant font-body">Cítrico</span>
                            <p class="text-xs font-bold text-tertiary">{{ $insights['citrus'] }}%</p>
                        </div>
                        <div>
                            <div class="w-3 h-3 rounded-full bg-tertiary-fixed-dim mx-auto mb-1"></div>
                            <span class="text-[10px] text-on-surface-variant font-body">Floral/Otro</span>
                            <p class="text-xs font-bold text-tertiary-fixed-dim">{{ $insights['floral'] }}%</p>
                        </div>
                    </div>
                </div>

                <!-- Customer CRM Search & Table -->
                <div class="lg:col-span-2 bg-surface-container-low border border-outline-variant/20 rounded-2xl p-8">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                        <div>
                            <h3 class="text-lg font-bold font-headline">CRM de Clientes</h3>
                            <p class="text-xs text-on-surface-variant font-body">Gestión de semillas de fidelidad y perfiles aromáticos.</p>
                        </div>
                        <!-- Search Bar -->
                        <div class="relative w-full md:w-64">
                            <span class="material-symbols-outlined absolute left-3 top-2.5 text-on-surface-variant text-sm">search</span>
                            <input type="text" wire:model.live="searchCustomer" placeholder="Buscar cliente..." class="w-full pl-9 pr-4 py-2 border border-outline-variant/30 bg-surface rounded-full text-xs font-body focus:outline-none focus:border-primary">
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-outline-variant/20 text-xs text-on-surface-variant uppercase font-bold font-body">
                                    <th class="py-3">Cliente</th>
                                    <th class="py-3">Perfil Olfativo</th>
                                    <th class="py-3 text-center">Semillas</th>
                                    <th class="py-3 text-right">Total Compras</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant/10 text-xs font-body">
                                @forelse($this->customers as $customer)
                                    <tr class="hover:bg-surface-container-high/50 transition-colors">
                                        <td class="py-4 flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold">
                                                {{ substr($customer['name'], 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-on-surface">{{ $customer['name'] }}</p>
                                                <p class="text-on-surface-variant text-[11px]">{{ $customer['email'] }}</p>
                                            </div>
                                        </td>
                                        <td class="py-4">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold {{ str_contains($customer['profile'], 'Amaderado') ? 'bg-secondary-container text-on-secondary-container' : (str_contains($customer['profile'], 'Cítrico') ? 'bg-primary-container text-on-primary-container' : 'bg-surface-container-high text-on-surface-variant') }}">
                                                <span class="material-symbols-outlined text-[12px]">spa</span>
                                                {{ $customer['profile'] }}
                                            </span>
                                        </td>
                                        <td class="py-4 text-center font-bold text-primary">
                                            {{ $customer['semillas'] }}
                                        </td>
                                        <td class="py-4 text-right">
                                            <p class="font-bold text-on-surface">${{ number_format($customer['total_spent'], 0, ',', '.') }}</p>
                                            <p class="text-on-surface-variant text-[10px]">{{ $customer['purchases_count'] }} pedidos</p>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-6 text-center text-on-surface-variant">No se encontraron clientes matching.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Tab 2: Catálogo de Productos -->
    @if($activeTab === 'products')
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 animate-fade-in">
            <!-- Product Alert Feedback -->
            @if (session()->has('product_success'))
                <div class="lg:col-span-12 p-4 bg-primary/10 text-primary rounded-xl border border-primary/20 flex items-start gap-3 shadow-sm mb-2 font-body">
                    <span class="material-symbols-outlined mt-0.5" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    <div>
                        <h4 class="font-bold text-sm">Operación Explicita</h4>
                        <p class="text-xs">{{ session('product_success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Left: Add/Edit Product Panel -->
            <div class="lg:col-span-4 bg-surface-container-low border border-outline-variant/20 rounded-2xl p-8 h-fit">
                @if($editingProductId)
                    <!-- Edit Mode -->
                    <h3 class="text-lg font-bold font-headline mb-6 flex items-center gap-2 text-primary">
                        <span class="material-symbols-outlined">edit</span>
                        Editar Producto
                    </h3>
                    <form wire:submit.prevent="saveEdit" class="space-y-4 font-body">
                        <div>
                            <label class="text-xs font-bold text-on-surface-variant mb-1.5 block">Nombre del Perfume</label>
                            <input type="text" wire:model="editName" class="w-full px-4 py-2.5 border border-outline-variant/30 bg-surface rounded-xl text-sm focus:outline-none focus:border-primary">
                            @error('editName') <span class="text-error text-xs block mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-bold text-on-surface-variant mb-1.5 block">Precio ($ ARS)</label>
                            <input type="number" wire:model="editPrice" class="w-full px-4 py-2.5 border border-outline-variant/30 bg-surface rounded-xl text-sm focus:outline-none focus:border-primary">
                            @error('editPrice') <span class="text-error text-xs block mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-bold text-on-surface-variant mb-1.5 block">Descripción</label>
                            <textarea wire:model="editDescription" rows="3" class="w-full px-4 py-2.5 border border-outline-variant/30 bg-surface rounded-xl text-sm focus:outline-none focus:border-primary"></textarea>
                            @error('editDescription') <span class="text-error text-xs block mt-1">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- Olfactory profile ratios -->
                        <div class="border-t border-outline-variant/20 pt-4 space-y-3">
                            <span class="text-xs font-bold text-on-surface-variant block mb-1">Afinidades Olfativas (%)</span>
                            <div>
                                <div class="flex justify-between text-[11px] mb-1">
                                    <span class="font-bold text-primary">Amaderado</span>
                                    <span>{{ $editWood }}%</span>
                                </div>
                                <input type="range" min="0" max="100" wire:model="editWood" class="w-full accent-primary">
                            </div>
                            <div>
                                <div class="flex justify-between text-[11px] mb-1">
                                    <span class="font-bold text-tertiary">Cítrico</span>
                                    <span>{{ $editCitrus }}%</span>
                                </div>
                                <input type="range" min="0" max="100" wire:model="editCitrus" class="w-full accent-tertiary">
                            </div>
                            <div>
                                <div class="flex justify-between text-[11px] mb-1">
                                    <span class="font-bold text-tertiary-fixed-dim">Floral</span>
                                    <span>{{ $editFloral }}%</span>
                                </div>
                                <input type="range" min="0" max="100" wire:model="editFloral" class="w-full accent-tertiary-fixed-dim">
                            </div>
                        </div>

                        <div class="flex gap-3 pt-4 border-t border-outline-variant/20">
                            <button type="button" wire:click="cancelEdit" class="flex-1 px-4 py-2.5 border border-outline-variant rounded-full text-xs font-bold text-on-surface-variant hover:bg-surface-container-high transition-colors">Cancelar</button>
                            <button type="submit" class="flex-1 px-4 py-2.5 bg-primary text-on-primary rounded-full text-xs font-bold hover:shadow-md transition-all">Guardar</button>
                        </div>
                    </form>
                @else
                    <!-- Create Mode -->
                    <h3 class="text-lg font-bold font-headline mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined">add_circle</span>
                        Añadir Nuevo Perfume
                    </h3>
                    <form wire:submit.prevent="addProduct" class="space-y-4 font-body">
                        <div>
                            <label class="text-xs font-bold text-on-surface-variant mb-1.5 block">Nombre</label>
                            <input type="text" wire:model="newName" placeholder="Ej: Oud Mystique" class="w-full px-4 py-2.5 border border-outline-variant/30 bg-surface rounded-xl text-sm focus:outline-none focus:border-primary">
                            @error('newName') <span class="text-error text-xs block mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-bold text-on-surface-variant mb-1.5 block">Precio ($ ARS)</label>
                            <input type="number" wire:model="newPrice" placeholder="Ej: 38000" class="w-full px-4 py-2.5 border border-outline-variant/30 bg-surface rounded-xl text-sm focus:outline-none focus:border-primary">
                            @error('newPrice') <span class="text-error text-xs block mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-bold text-on-surface-variant mb-1.5 block">Familia Predominante</label>
                            <select wire:model="newFamily" class="w-full px-4 py-2.5 border border-outline-variant/30 bg-surface rounded-xl text-sm focus:outline-none focus:border-primary">
                                <option value="Amaderado">Amaderado</option>
                                <option value="Cítrico">Cítrico</option>
                                <option value="Floral">Floral</option>
                                <option value="Oriental">Oriental</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-on-surface-variant mb-1.5 block">Descripción</label>
                            <textarea wire:model="newDescription" placeholder="Detalles de acordes y esencias..." rows="3" class="w-full px-4 py-2.5 border border-outline-variant/30 bg-surface rounded-xl text-sm focus:outline-none focus:border-primary"></textarea>
                            @error('newDescription') <span class="text-error text-xs block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Olfactory profile ratios -->
                        <div class="border-t border-outline-variant/20 pt-4 space-y-3">
                            <span class="text-xs font-bold text-on-surface-variant block mb-1">Afinidades Olfativas (%)</span>
                            <div>
                                <div class="flex justify-between text-[11px] mb-1">
                                    <span class="font-bold text-primary">Amaderado</span>
                                    <span>{{ $newWood }}%</span>
                                </div>
                                <input type="range" min="0" max="100" wire:model="newWood" class="w-full accent-primary">
                            </div>
                            <div>
                                <div class="flex justify-between text-[11px] mb-1">
                                    <span class="font-bold text-tertiary">Cítrico</span>
                                    <span>{{ $newCitrus }}%</span>
                                </div>
                                <input type="range" min="0" max="100" wire:model="newCitrus" class="w-full accent-tertiary">
                            </div>
                            <div>
                                <div class="flex justify-between text-[11px] mb-1">
                                    <span class="font-bold text-tertiary-fixed-dim">Floral</span>
                                    <span>{{ $newFloral }}%</span>
                                </div>
                                <input type="range" min="0" max="100" wire:model="newFloral" class="w-full accent-tertiary-fixed-dim">
                            </div>
                        </div>

                        <button type="submit" class="w-full mt-4 py-3 bg-primary text-on-primary rounded-full text-xs font-bold hover:shadow-md transition-all">Crear Producto</button>
                    </form>
                @endif
            </div>

            <!-- Right: Products List -->
            <div class="lg:col-span-8 bg-surface-container-low border border-outline-variant/20 rounded-2xl p-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                    <div>
                        <h3 class="text-lg font-bold font-headline">Perfumes en Catálogo</h3>
                        <p class="text-xs text-on-surface-variant font-body">Ajusta el precio, stock y afinidades de cada decant.</p>
                    </div>
                    <!-- Search Bar -->
                    <div class="relative w-full md:w-64">
                        <span class="material-symbols-outlined absolute left-3 top-2.5 text-on-surface-variant text-sm">search</span>
                        <input type="text" wire:model.live="searchProduct" placeholder="Buscar perfume..." class="w-full pl-9 pr-4 py-2 border border-outline-variant/30 bg-surface rounded-full text-xs font-body focus:outline-none focus:border-primary">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    @forelse($this->products as $p)
                        <div class="bg-surface border border-outline-variant/20 rounded-xl p-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 hover:shadow-md transition-all">
                            <div class="flex items-center gap-4">
                                <img src="{{ $p['image'] }}" class="w-16 h-16 object-cover rounded-lg bg-surface-container border border-outline-variant/10">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-bold font-headline text-on-surface">{{ $p['name'] }}</h4>
                                        <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider {{ $p['family_class'] }}">{{ $p['family'] }}</span>
                                    </div>
                                    <p class="text-xs text-on-surface-variant font-body max-w-md mt-1 leading-relaxed">{{ $p['description'] }}</p>
                                    <div class="flex items-center gap-4 mt-2 text-[10px] font-bold text-on-surface-variant font-body">
                                        <span class="flex items-center gap-1"><div class="w-2 h-2 rounded-full bg-[#4a7c59]"></div> Madera: {{ $p['wood'] }}%</span>
                                        <span class="flex items-center gap-1"><div class="w-2 h-2 rounded-full bg-[#705c30]"></div> Cítrico: {{ $p['citrus'] }}%</span>
                                        <span class="flex items-center gap-1"><div class="w-2 h-2 rounded-full bg-[#dcc48e]"></div> Floral: {{ $p['floral'] }}%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex md:flex-col items-end gap-3 w-full md:w-auto border-t md:border-t-0 border-outline-variant/15 pt-3 md:pt-0">
                                <div class="flex justify-between md:justify-end items-center gap-4 w-full md:w-auto">
                                    <span class="text-base font-display font-bold text-primary">${{ number_format($p['price'], 0, ',', '.') }}</span>
                                    
                                    <!-- Stock Toggle Switch -->
                                    <button wire:click="toggleStock({{ $p['id'] }})" class="flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold border transition-all {{ $p['in_stock'] ? 'border-primary text-primary hover:bg-primary/5' : 'border-error text-error hover:bg-error/5' }}">
                                        <span class="material-symbols-outlined text-[12px]">{{ $p['in_stock'] ? 'check' : 'close' }}</span>
                                        {{ $p['in_stock'] ? 'En Stock' : 'Sin Stock' }}
                                    </button>
                                </div>
                                <div class="flex gap-2 w-full md:w-auto">
                                    <button wire:click="startEdit({{ $p['id'] }})" class="flex-1 md:flex-initial px-4 py-2 border border-outline-variant rounded-full text-[11px] font-bold text-on-surface-variant hover:bg-surface-container-high transition-colors">Editar</button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-on-surface-variant font-body">No hay productos en el catálogo.</div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

    <!-- Tab 3: Pedidos -->
    @if($activeTab === 'orders')
        <div class="bg-surface-container-low border border-outline-variant/20 rounded-2xl p-8 animate-fade-in">
            @if (session()->has('order_success'))
                <div class="p-4 bg-primary/10 text-primary rounded-xl border border-primary/20 flex items-start gap-3 shadow-sm mb-6 font-body">
                    <span class="material-symbols-outlined mt-0.5" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    <div>
                        <h4 class="font-bold text-sm">Acción Exitosa</h4>
                        <p class="text-xs">{{ session('order_success') }}</p>
                    </div>
                </div>
            @endif

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                <div>
                    <h3 class="text-lg font-bold font-headline">Registro de Pedidos</h3>
                    <p class="text-xs text-on-surface-variant font-body">Control de envíos y estado de cobros de Mercado Pago.</p>
                </div>
                <!-- Status Filter -->
                <div class="flex bg-surface p-1 rounded-full border border-outline-variant/30 text-xs font-bold font-body">
                    <button wire:click="$set('filterOrderStatus', 'all')" class="px-4 py-1.5 rounded-full {{ $filterOrderStatus === 'all' ? 'bg-primary text-on-primary' : 'text-on-surface-variant' }}">Todos</button>
                    <button wire:click="$set('filterOrderStatus', 'Pendiente')" class="px-4 py-1.5 rounded-full {{ $filterOrderStatus === 'Pendiente' ? 'bg-primary text-on-primary' : 'text-on-surface-variant' }}">Pendientes</button>
                    <button wire:click="$set('filterOrderStatus', 'Pagado')" class="px-4 py-1.5 rounded-full {{ $filterOrderStatus === 'Pagado' ? 'bg-primary text-on-primary' : 'text-on-surface-variant' }}">Pagados</button>
                    <button wire:click="$set('filterOrderStatus', 'Enviado')" class="px-4 py-1.5 rounded-full {{ $filterOrderStatus === 'Enviado' ? 'bg-primary text-on-primary' : 'text-on-surface-variant' }}">Enviados</button>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-outline-variant/20 text-xs text-on-surface-variant uppercase font-bold font-body">
                            <th class="py-3">Fecha</th>
                            <th class="py-3">Pedido ID</th>
                            <th class="py-3">Cliente</th>
                            <th class="py-3">Items</th>
                            <th class="py-3">Total</th>
                            <th class="py-3">Estado</th>
                            <th class="py-3 text-right">Acciones Rápidas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/10 text-xs font-body">
                        @forelse($this->orders as $o)
                            <tr class="hover:bg-surface-container-high/40 transition-colors">
                                <td class="py-4 text-on-surface-variant">{{ $o['date'] }}</td>
                                <td class="py-4 font-bold text-on-surface">{{ $o['id'] }}</td>
                                <td class="py-4 font-bold text-on-surface">{{ $o['customer'] }}</td>
                                <td class="py-4 text-on-surface-variant">{{ $o['items'] }}</td>
                                <td class="py-4 font-bold text-primary">${{ number_format($o['total'], 0, ',', '.') }}</td>
                                <td class="py-4">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold {{ $o['status'] === 'Enviado' ? 'bg-secondary-container text-on-secondary-container' : ($o['status'] === 'Pagado' ? 'bg-primary-container text-on-primary-container' : 'bg-error-container text-on-error-container') }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $o['status'] === 'Enviado' ? 'bg-primary' : ($o['status'] === 'Pagado' ? 'bg-tertiary' : 'bg-error') }}"></span>
                                        {{ $o['status'] }}
                                    </span>
                                </td>
                                <td class="py-4 text-right flex justify-end gap-2">
                                    @if($o['status'] === 'Pendiente')
                                        <button wire:click="updateOrderStatus('{{ $o['id'] }}', 'Pagado')" class="px-3 py-1 bg-primary text-on-primary rounded-full text-[10px] font-bold hover:shadow transition-all">Marcar Pagado</button>
                                    @elseif($o['status'] === 'Pagado')
                                        <button wire:click="updateOrderStatus('{{ $o['id'] }}', 'Enviado')" class="px-3 py-1 bg-primary text-on-primary rounded-full text-[10px] font-bold hover:shadow transition-all">Marcar Enviado</button>
                                    @else
                                        <span class="text-on-surface-variant font-bold text-[10px]">Listo</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-6 text-center text-on-surface-variant">No se encontraron pedidos con este filtro.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.3s ease-out forwards;
    }
</style>
