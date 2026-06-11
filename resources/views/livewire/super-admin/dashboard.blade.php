<div class="w-full bg-white min-h-screen py-10 px-6 max-w-[1200px] mx-auto font-body">

    <!-- Header -->
    <header class="flex items-center gap-4 mb-8">
        <div class="w-12 h-12 rounded-lg bg-indigo-600 text-white flex items-center justify-center shadow-lg shadow-indigo-600/30">
            <span class="material-symbols-outlined text-[28px]">shield</span>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 leading-tight">Panel de Super Admin</h1>
            <p class="text-sm text-gray-500">Configuración global y permisos por Tenant</p>
        </div>
    </header>

    <!-- Select Tenant -->
    <div class="border border-gray-100 rounded-lg p-6 shadow-sm mb-8 bg-white">
        <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-4">
            <span class="material-symbols-outlined text-indigo-600 text-lg">manage_accounts</span>
            Seleccionar Admin / Tenant
        </label>
        <div class="relative">
            <select wire:model.live="selectedTenant" class="w-full appearance-none border border-gray-200 rounded-lg bg-gray-50 px-4 py-3 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors">
                <option value="">Seleccione un administrador...</option>
                @foreach($this->tenants as $tenant)
                    <option value="{{ $tenant->id }}">{{ $tenant->name }} ({{ $tenant->email }})</option>
                @endforeach
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                <span class="material-symbols-outlined text-sm">expand_more</span>
            </div>
        </div>
    </div>

    <!-- Info Alert -->
    <div class="bg-[#f5f3ff] border border-indigo-100 rounded-lg p-5 flex items-start gap-4 mb-10 shadow-sm">
        <div class="w-6 h-6 rounded-full bg-indigo-500 text-white flex items-center justify-center flex-shrink-0 mt-0.5">
            <span class="text-xs font-bold font-serif italic">i</span>
        </div>
        <div class="text-indigo-900/90 text-sm leading-relaxed">
            <h4 class="font-bold mb-1">¿Cómo funciona el panel?</h4>
            <p>Haz clic en el título de cualquier categoría para desplegar u ocultar los elementos de control.</p>
            <p><span class="font-semibold">Módulos:</span> Habilita o deshabilita accesos en la barra lateral y rutas de administración.</p>
            <p><span class="font-semibold">Métricas:</span> Define cuáles indicadores y gráficos están visibles en el dashboard principal del tenant.</p>
            <p>El botón <span class="font-semibold">todo</span> permite alternar de forma masiva el estado de todas las métricas de una categoría específica sin necesidad de desplegarla.</p>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex items-center gap-8 border-b border-gray-200 mb-8 overflow-x-auto">
        <button wire:click="switchTab('modulos')" class="pb-4 text-sm font-bold flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'modulos' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-400 hover:text-gray-600' }}">
            <span class="material-symbols-outlined text-[18px]">category</span>
            MÓDULOS Y MÉTRICAS
        </button>
        <button wire:click="switchTab('tienda')" class="pb-4 text-sm font-bold flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'tienda' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-400 hover:text-gray-600' }}">
            <span class="material-symbols-outlined text-[18px]">storefront</span>
            CONFIGURACIÓN DE TIENDA
        </button>
        <button wire:click="switchTab('usuarios')" class="pb-4 text-sm font-bold flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'usuarios' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-400 hover:text-gray-600' }}">
            <span class="material-symbols-outlined text-[18px]">group</span>
            USUARIOS
        </button>
        <button wire:click="switchTab('limites')" class="pb-4 text-sm font-bold flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'limites' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-400 hover:text-gray-600' }}">
            <span class="material-symbols-outlined text-[18px]">monitoring</span>
            LÍMITES Y FACTURACIÓN
        </button>
        <button wire:click="switchTab('auditoria')" class="pb-4 text-sm font-bold flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'auditoria' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-400 hover:text-gray-600' }}">
            <span class="material-symbols-outlined text-[18px]">history</span>
            AUDITORÍA
        </button>
    </div>

    @if(session()->has('message'))
        <div class="mb-6 p-4 bg-green-50 text-green-800 rounded-lg text-sm font-bold border border-green-200">
            {{ session('message') }}
        </div>
    @endif

    <!-- Action Buttons -->
    @if($activeTab === 'modulos')
        <div class="flex flex-wrap gap-4">
            <!-- Plan Semilla -->
            <button wire:click="actionClicked('Plan Semilla')" class="flex items-center gap-2 px-5 py-2.5 rounded text-sm font-bold bg-[#fff8e1] text-[#ffb300] border border-[#ffecb3] hover:bg-[#ffecb3]/50 transition-colors">
                <span class="material-symbols-outlined text-[18px]">inventory_2</span>
                Plan Semilla
            </button>
            
            <!-- Plan Flor -->
            <button wire:click="actionClicked('Plan Flor')" class="flex items-center gap-2 px-5 py-2.5 rounded text-sm font-bold bg-[#fce4ec] text-[#e91e63] border border-[#f8bbd0] hover:bg-[#f8bbd0]/50 transition-colors">
                <span class="material-symbols-outlined text-[18px]">local_florist</span>
                Plan Flor
            </button>
            
            <!-- Plan Extracto -->
            <button wire:click="actionClicked('Plan Extracto')" class="flex items-center gap-2 px-5 py-2.5 rounded text-sm font-bold bg-[#f3e5f5] text-[#9c27b0] border border-[#e1bee7] hover:bg-[#e1bee7]/50 transition-colors">
                <span class="material-symbols-outlined text-[18px]">workspace_premium</span>
                Plan Extracto
            </button>
            
            <!-- Toggle Documentación IA -->
            @if($iaDocumentationEnabled)
            <button wire:click="actionClicked('Deshabilitar IA')" class="flex items-center gap-2 px-5 py-2.5 rounded text-sm font-bold bg-[#e3f2fd] text-[#1976d2] border border-[#bbdefb] hover:bg-[#bbdefb]/50 transition-colors">
                <span class="material-symbols-outlined text-[18px]">auto_fix_off</span>
                Deshabilitar Documentación IA
            </button>
            @else
            <button wire:click="actionClicked('Habilitar IA')" class="flex items-center gap-2 px-5 py-2.5 rounded text-sm font-bold bg-[#e3f2fd] text-[#1976d2] border border-[#bbdefb] hover:bg-[#bbdefb]/50 transition-colors">
                <span class="material-symbols-outlined text-[18px]">auto_fix_high</span>
                Habilitar Documentación IA
            </button>
            @endif
            
            <!-- Deshabilitar Chatbot -->
            <button wire:click="actionClicked('Deshabilitar Chatbot')" class="flex items-center gap-2 px-5 py-2.5 rounded text-sm font-bold bg-[#e8f5e9] text-[#388e3c] border border-[#c8e6c9] hover:bg-[#c8e6c9]/50 transition-colors">
                <span class="material-symbols-outlined text-[18px]">smart_toy</span>
                Deshabilitar Chatbot
            </button>
            
            <!-- Deshabilitar Todo -->
            <button wire:click="actionClicked('Deshabilitar Todo')" class="flex items-center gap-2 px-5 py-2.5 rounded text-sm font-bold bg-[#ffebee] text-[#d32f2f] border border-[#ffcdd2] hover:bg-[#ffcdd2]/50 transition-colors">
                <span class="material-symbols-outlined text-[18px]">toggle_off</span>
                Deshabilitar Todo
            </button>
        </div>
        
        <!-- Metrics Section -->
        <div class="mt-12">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded bg-indigo-100 text-indigo-700 flex items-center justify-center">
                    <span class="material-symbols-outlined text-xl">bar_chart</span>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 leading-none">Métricas del dashboard</h2>
                    <p class="text-xs text-gray-500 mt-1">Control por tenant</p>
                </div>
                <div class="ml-auto flex items-center gap-2">
                    <button wire:click="$toggle('showMetricsPreview')" class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition-colors shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">dashboard</span>
                        Métricas
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-indigo-600 px-4 py-2 rounded-lg text-sm font-bold transition-colors">
                        <span class="material-symbols-outlined text-[18px]">speed</span>
                        Dashboard
                    </a>
                </div>
            </div>

            @if($showMetricsPreview)
            <!-- Preview Dashboard Modal/Section -->
            <div class="mb-8 p-6 bg-gray-50 border border-gray-200 rounded-xl animate-fade-in relative">
                <!-- Close Button -->
                <button wire:click="$set('showMetricsPreview', false)" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
                
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4 border-b border-gray-200 pb-2">Vista Previa de Métricas</h3>

                <div class="mb-6 p-6 bg-indigo-600 text-white rounded-xl flex items-center justify-between shadow-md">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white text-indigo-600 rounded flex items-center justify-center font-bold text-lg">KA</div>
                        <div>
                            <h2 class="text-xl font-bold">¡Hola de nuevo, Katrix Super Admin!</h2>
                            <p class="text-sm text-indigo-100">Este es el resumen de operaciones y métricas de tu tienda.</p>
                        </div>
                    </div>
                    <div class="bg-indigo-500/50 px-4 py-2 rounded flex items-center gap-2 text-sm font-bold border border-indigo-400">
                        <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                        Panel de Control Activo
                    </div>
                </div>

                @include('livewire.metrics-dashboard')
            </div>
            @endif

            @php
                $ventasActivas = (!empty($enabledMetrics['Ingresos totales']) ? 1 : 0) + (!empty($enabledMetrics['Ticket promedio']) ? 1 : 0) + (!empty($enabledMetrics['Ventas vs mes anterior']) ? 1 : 0) + (!empty($enabledMetrics['Gráfico de ventas']) ? 1 : 0);
                $ordenesActivas = (!empty($enabledMetrics['Órdenes del día']) ? 1 : 0) + (!empty($enabledMetrics['Órdenes pendientes']) ? 1 : 0) + (!empty($enabledMetrics['Órdenes canceladas']) ? 1 : 0);
                $logisticaActivas = (!empty($enabledMetrics['Envíos activos']) ? 1 : 0);
                $productosActivas = (!empty($enabledMetrics['Más vendidos']) ? 1 : 0) + (!empty($enabledMetrics['Stock bajo']) ? 1 : 0) + (!empty($enabledMetrics['Más visitados']) ? 1 : 0);
                $usuariosActivas = (!empty($enabledMetrics['Nuevos registros']) ? 1 : 0) + (!empty($enabledMetrics['Clientes recurrentes']) ? 1 : 0);
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Ventas -->
                <div class="border border-gray-100 rounded bg-white p-6 flex flex-col items-center text-center shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded bg-indigo-50 text-indigo-600 flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined">trending_up</span>
                    </div>
                    <h3 class="text-xs font-bold text-gray-800 tracking-wider mb-2">VENTAS</h3>
                    <span class="bg-indigo-50 text-indigo-700 text-xs font-semibold px-3 py-1 rounded">{{ $ventasActivas }} / 4 activas</span>
                    <button wire:click="toggleMetric('ventas')" class="mt-4 flex items-center justify-center gap-1 bg-indigo-50 text-indigo-600 text-[11px] font-bold px-3 py-1 rounded hover:bg-indigo-100 transition-colors uppercase tracking-wider">
                        todo <span class="material-symbols-outlined text-[14px]">{{ $expandedMetric === 'ventas' ? 'expand_less' : 'expand_more' }}</span>
                    </button>
                </div>
                
                <!-- Órdenes -->
                <div class="border border-gray-100 rounded bg-white p-6 flex flex-col items-center text-center shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded bg-indigo-50 text-indigo-600 flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined">shopping_cart</span>
                    </div>
                    <h3 class="text-xs font-bold text-gray-800 tracking-wider mb-2">ÓRDENES</h3>
                    <span class="bg-indigo-50 text-indigo-700 text-xs font-semibold px-3 py-1 rounded">{{ $ordenesActivas }} / 3 activas</span>
                    <button wire:click="toggleMetric('ordenes')" class="mt-4 flex items-center justify-center gap-1 bg-indigo-50 text-indigo-600 text-[11px] font-bold px-3 py-1 rounded hover:bg-indigo-100 transition-colors uppercase tracking-wider">
                        todo <span class="material-symbols-outlined text-[14px]">{{ $expandedMetric === 'ordenes' ? 'expand_less' : 'expand_more' }}</span>
                    </button>
                </div>

                <!-- Logística -->
                <div class="border border-gray-100 rounded bg-white p-6 flex flex-col items-center text-center shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded bg-indigo-50 text-indigo-600 flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined">local_shipping</span>
                    </div>
                    <h3 class="text-xs font-bold text-gray-800 tracking-wider mb-2">LOGÍSTICA</h3>
                    <span class="bg-indigo-50 text-indigo-700 text-xs font-semibold px-3 py-1 rounded">{{ $logisticaActivas }} / 1 activas</span>
                    <button wire:click="toggleMetric('logistica')" class="mt-4 flex items-center justify-center gap-1 bg-indigo-50 text-indigo-600 text-[11px] font-bold px-3 py-1 rounded hover:bg-indigo-100 transition-colors uppercase tracking-wider">
                        todo <span class="material-symbols-outlined text-[14px]">{{ $expandedMetric === 'logistica' ? 'expand_less' : 'expand_more' }}</span>
                    </button>
                </div>

                <!-- Productos -->
                <div class="border border-gray-100 rounded bg-white p-6 flex flex-col items-center text-center shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded bg-indigo-50 text-indigo-600 flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined">inventory_2</span>
                    </div>
                    <h3 class="text-xs font-bold text-gray-800 tracking-wider mb-2">PRODUCTOS</h3>
                    <span class="bg-indigo-50 text-indigo-700 text-xs font-semibold px-3 py-1 rounded">{{ $productosActivas }} / 3 activas</span>
                    <button wire:click="toggleMetric('productos')" class="mt-4 flex items-center justify-center gap-1 bg-indigo-50 text-indigo-600 text-[11px] font-bold px-3 py-1 rounded hover:bg-indigo-100 transition-colors uppercase tracking-wider">
                        todo <span class="material-symbols-outlined text-[14px]">{{ $expandedMetric === 'productos' ? 'expand_less' : 'expand_more' }}</span>
                    </button>
                </div>

                <!-- Usuarios -->
                <div class="border border-gray-100 rounded bg-white p-6 flex flex-col items-center text-center shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded bg-indigo-50 text-indigo-600 flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined">group</span>
                    </div>
                    <h3 class="text-xs font-bold text-gray-800 tracking-wider mb-2">USUARIOS</h3>
                    <span class="bg-indigo-50 text-indigo-700 text-xs font-semibold px-3 py-1 rounded">{{ $usuariosActivas }} / 2 activas</span>
                    <button wire:click="toggleMetric('usuarios')" class="mt-4 flex items-center justify-center gap-1 bg-indigo-50 text-indigo-600 text-[11px] font-bold px-3 py-1 rounded hover:bg-indigo-100 transition-colors uppercase tracking-wider">
                        todo <span class="material-symbols-outlined text-[14px]">{{ $expandedMetric === 'usuarios' ? 'expand_less' : 'expand_more' }}</span>
                    </button>
                </div>
            </div>

            @if($expandedMetric === 'ordenes')
            <div class="mt-6 border border-indigo-200 rounded bg-white shadow-sm animate-fade-in">
                <!-- Header -->
                <div class="flex items-center justify-between p-4 border-b border-indigo-50 bg-indigo-50/30">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-indigo-600">shopping_cart</span>
                        <h3 class="text-sm font-bold text-gray-800 tracking-wider">ÓRDENES</h3>
                        <span class="bg-indigo-50 text-indigo-700 text-[10px] font-semibold px-2 py-0.5 rounded">{{ $ordenesActivas }} / 3 activas</span>
                    </div>
                    <button wire:click="toggleMetric('ordenes')" class="flex items-center justify-center gap-1 bg-indigo-100 text-indigo-600 text-[11px] font-bold px-3 py-1 rounded hover:bg-indigo-200 transition-colors uppercase tracking-wider">
                        todo <span class="material-symbols-outlined text-[14px]">expand_less</span>
                    </button>
                </div>
                <!-- List -->
                <div class="divide-y divide-gray-50">
                    <!-- Item 1 -->
                    <div class="flex items-center gap-4 p-4 hover:bg-gray-50/50 transition-colors">
                        <!-- Toggle switch ON -->
                        <div class="w-10 h-5 {{ !empty($enabledMetrics['Órdenes del día']) ? 'bg-indigo-600' : 'bg-gray-300' }} rounded-full relative cursor-pointer transition-colors duration-200" wire:click="toggleMetricVisibility('Órdenes del día')">
                            <div class="w-4 h-4 bg-white rounded-full absolute top-0.5 {{ !empty($enabledMetrics['Órdenes del día']) ? 'right-0.5' : 'left-0.5' }} shadow-sm transition-all duration-200"></div>
                        </div>
                        <div class="w-8 h-8 rounded bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[16px]">receipt_long</span>
                        </div>
                        <span class="text-sm font-bold text-gray-700">Órdenes del día</span>
                    </div>
                    <!-- Item 2 -->
                    <div class="flex items-center gap-4 p-4 hover:bg-gray-50/50 transition-colors">
                        <!-- Toggle switch ON -->
                        <div class="w-10 h-5 {{ !empty($enabledMetrics['Órdenes pendientes']) ? 'bg-indigo-600' : 'bg-gray-300' }} rounded-full relative cursor-pointer transition-colors duration-200" wire:click="toggleMetricVisibility('Órdenes pendientes')">
                            <div class="w-4 h-4 bg-white rounded-full absolute top-0.5 {{ !empty($enabledMetrics['Órdenes pendientes']) ? 'right-0.5' : 'left-0.5' }} shadow-sm transition-all duration-200"></div>
                        </div>
                        <div class="w-8 h-8 rounded bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[16px]">schedule</span>
                        </div>
                        <span class="text-sm font-bold text-gray-700">Órdenes pendientes</span>
                    </div>
                    <!-- Item 3 -->
                    <div class="flex items-center gap-4 p-4 hover:bg-gray-50/50 transition-colors">
                        <!-- Toggle switch ON -->
                        <div class="w-10 h-5 {{ !empty($enabledMetrics['Órdenes canceladas']) ? 'bg-indigo-600' : 'bg-gray-300' }} rounded-full relative cursor-pointer transition-colors duration-200" wire:click="toggleMetricVisibility('Órdenes canceladas')">
                            <div class="w-4 h-4 bg-white rounded-full absolute top-0.5 {{ !empty($enabledMetrics['Órdenes canceladas']) ? 'right-0.5' : 'left-0.5' }} shadow-sm transition-all duration-200"></div>
                        </div>
                        <div class="w-8 h-8 rounded bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[16px]">cancel</span>
                        </div>
                        <span class="text-sm font-bold text-gray-700">Órdenes canceladas</span>
                    </div>
                </div>
            </div>
            @endif

            @if($expandedMetric === 'ventas')
            <div class="mt-6 border border-indigo-200 rounded bg-white shadow-sm animate-fade-in">
                <!-- Header -->
                <div class="flex items-center justify-between p-4 border-b border-indigo-50 bg-indigo-50/30">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-indigo-600">trending_up</span>
                        <h3 class="text-sm font-bold text-gray-800 tracking-wider">VENTAS</h3>
                        <span class="bg-indigo-50 text-indigo-700 text-[10px] font-semibold px-2 py-0.5 rounded">{{ $ventasActivas }} / 4 activas</span>
                    </div>
                    <button wire:click="toggleMetric('ventas')" class="flex items-center justify-center gap-1 bg-indigo-100 text-indigo-600 text-[11px] font-bold px-3 py-1 rounded hover:bg-indigo-200 transition-colors uppercase tracking-wider">
                        todo <span class="material-symbols-outlined text-[14px]">expand_less</span>
                    </button>
                </div>
                <!-- List -->
                <div class="divide-y divide-gray-50">
                    <!-- Item 1 -->
                    <div class="flex items-center gap-4 p-4 hover:bg-gray-50/50 transition-colors">
                        <div class="w-10 h-5 {{ !empty($enabledMetrics['Ingresos totales']) ? 'bg-indigo-600' : 'bg-gray-300' }} rounded-full relative cursor-pointer transition-colors duration-200" wire:click="toggleMetricVisibility('Ingresos totales')">
                            <div class="w-4 h-4 bg-white rounded-full absolute top-0.5 {{ !empty($enabledMetrics['Ingresos totales']) ? 'right-0.5' : 'left-0.5' }} shadow-sm transition-all duration-200"></div>
                        </div>
                        <div class="w-8 h-8 rounded bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[16px]">attach_money</span>
                        </div>
                        <span class="text-sm font-bold text-gray-700">Ingresos totales</span>
                    </div>
                    <!-- Item 2 -->
                    <div class="flex items-center gap-4 p-4 hover:bg-gray-50/50 transition-colors">
                        <div class="w-10 h-5 {{ !empty($enabledMetrics['Ticket promedio']) ? 'bg-indigo-600' : 'bg-gray-300' }} rounded-full relative cursor-pointer transition-colors duration-200" wire:click="toggleMetricVisibility('Ticket promedio')">
                            <div class="w-4 h-4 bg-white rounded-full absolute top-0.5 {{ !empty($enabledMetrics['Ticket promedio']) ? 'right-0.5' : 'left-0.5' }} shadow-sm transition-all duration-200"></div>
                        </div>
                        <div class="w-8 h-8 rounded bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[16px]">receipt_long</span>
                        </div>
                        <span class="text-sm font-bold text-gray-700">Ticket promedio</span>
                    </div>
                    <!-- Item 3 -->
                    <div class="flex items-center gap-4 p-4 hover:bg-gray-50/50 transition-colors">
                        <div class="w-10 h-5 {{ !empty($enabledMetrics['Ventas vs mes anterior']) ? 'bg-indigo-600' : 'bg-gray-300' }} rounded-full relative cursor-pointer transition-colors duration-200" wire:click="toggleMetricVisibility('Ventas vs mes anterior')">
                            <div class="w-4 h-4 bg-white rounded-full absolute top-0.5 {{ !empty($enabledMetrics['Ventas vs mes anterior']) ? 'right-0.5' : 'left-0.5' }} shadow-sm transition-all duration-200"></div>
                        </div>
                        <div class="w-8 h-8 rounded bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[16px]">trending_up</span>
                        </div>
                        <span class="text-sm font-bold text-gray-700">Ventas vs mes anterior</span>
                    </div>
                    <!-- Item 4 -->
                    <div class="flex items-center gap-4 p-4 hover:bg-gray-50/50 transition-colors">
                        <div class="w-10 h-5 {{ !empty($enabledMetrics['Gráfico de ventas']) ? 'bg-indigo-600' : 'bg-gray-300' }} rounded-full relative cursor-pointer transition-colors duration-200" wire:click="toggleMetricVisibility('Gráfico de ventas')">
                            <div class="w-4 h-4 bg-white rounded-full absolute top-0.5 {{ !empty($enabledMetrics['Gráfico de ventas']) ? 'right-0.5' : 'left-0.5' }} shadow-sm transition-all duration-200"></div>
                        </div>
                        <div class="w-8 h-8 rounded bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[16px]">show_chart</span>
                        </div>
                        <span class="text-sm font-bold text-gray-700">Gráfico de ventas</span>
                    </div>
                </div>
            </div>
            @endif

            @if($expandedMetric === 'logistica')
            <div class="mt-6 border border-indigo-200 rounded bg-white shadow-sm animate-fade-in">
                <!-- Header -->
                <div class="flex items-center justify-between p-4 border-b border-indigo-50 bg-indigo-50/30">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-indigo-600">local_shipping</span>
                        <h3 class="text-sm font-bold text-gray-800 tracking-wider">LOGÍSTICA</h3>
                        <span class="bg-indigo-50 text-indigo-700 text-[10px] font-semibold px-2 py-0.5 rounded">{{ $logisticaActivas }} / 1 activas</span>
                    </div>
                    <button wire:click="toggleMetric('logistica')" class="flex items-center justify-center gap-1 bg-indigo-100 text-indigo-600 text-[11px] font-bold px-3 py-1 rounded hover:bg-indigo-200 transition-colors uppercase tracking-wider">
                        todo <span class="material-symbols-outlined text-[14px]">expand_less</span>
                    </button>
                </div>
                <!-- List -->
                <div class="divide-y divide-gray-50">
                    <!-- Item 1 -->
                    <div class="flex items-center gap-4 p-4 hover:bg-gray-50/50 transition-colors">
                        <div class="w-10 h-5 {{ !empty($enabledMetrics['Envíos activos']) ? 'bg-indigo-600' : 'bg-gray-300' }} rounded-full relative cursor-pointer transition-colors duration-200" wire:click="toggleMetricVisibility('Envíos activos')">
                            <div class="w-4 h-4 bg-white rounded-full absolute top-0.5 {{ !empty($enabledMetrics['Envíos activos']) ? 'right-0.5' : 'left-0.5' }} shadow-sm transition-all duration-200"></div>
                        </div>
                        <div class="w-8 h-8 rounded bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[16px]">local_shipping</span>
                        </div>
                        <span class="text-sm font-bold text-gray-700">Envíos activos</span>
                    </div>
                </div>
            </div>
            @endif

            @if($expandedMetric === 'productos')
            <div class="mt-6 border border-indigo-200 rounded bg-white shadow-sm animate-fade-in">
                <!-- Header -->
                <div class="flex items-center justify-between p-4 border-b border-indigo-50 bg-indigo-50/30">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-indigo-600">inventory_2</span>
                        <h3 class="text-sm font-bold text-gray-800 tracking-wider">PRODUCTOS</h3>
                        <span class="bg-indigo-50 text-indigo-700 text-[10px] font-semibold px-2 py-0.5 rounded">{{ $productosActivas }} / 3 activas</span>
                    </div>
                    <button wire:click="toggleMetric('productos')" class="flex items-center justify-center gap-1 bg-indigo-100 text-indigo-600 text-[11px] font-bold px-3 py-1 rounded hover:bg-indigo-200 transition-colors uppercase tracking-wider">
                        todo <span class="material-symbols-outlined text-[14px]">expand_less</span>
                    </button>
                </div>
                <!-- List -->
                <div class="divide-y divide-gray-50">
                    <!-- Item 1 -->
                    <div class="flex items-center gap-4 p-4 hover:bg-gray-50/50 transition-colors">
                        <div class="w-10 h-5 {{ !empty($enabledMetrics['Más vendidos']) ? 'bg-indigo-600' : 'bg-gray-300' }} rounded-full relative cursor-pointer transition-colors duration-200" wire:click="toggleMetricVisibility('Más vendidos')">
                            <div class="w-4 h-4 bg-white rounded-full absolute top-0.5 {{ !empty($enabledMetrics['Más vendidos']) ? 'right-0.5' : 'left-0.5' }} shadow-sm transition-all duration-200"></div>
                        </div>
                        <div class="w-8 h-8 rounded bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[16px]">stars</span>
                        </div>
                        <span class="text-sm font-bold text-gray-700">Más vendidos</span>
                    </div>
                    <!-- Item 2 -->
                    <div class="flex items-center gap-4 p-4 hover:bg-gray-50/50 transition-colors">
                        <div class="w-10 h-5 {{ !empty($enabledMetrics['Stock bajo']) ? 'bg-indigo-600' : 'bg-gray-300' }} rounded-full relative cursor-pointer transition-colors duration-200" wire:click="toggleMetricVisibility('Stock bajo')">
                            <div class="w-4 h-4 bg-white rounded-full absolute top-0.5 {{ !empty($enabledMetrics['Stock bajo']) ? 'right-0.5' : 'left-0.5' }} shadow-sm transition-all duration-200"></div>
                        </div>
                        <div class="w-8 h-8 rounded bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[16px]">warning</span>
                        </div>
                        <span class="text-sm font-bold text-gray-700">Stock bajo</span>
                    </div>
                    <!-- Item 3 -->
                    <div class="flex items-center gap-4 p-4 hover:bg-gray-50/50 transition-colors">
                        <div class="w-10 h-5 {{ !empty($enabledMetrics['Más visitados']) ? 'bg-indigo-600' : 'bg-gray-300' }} rounded-full relative cursor-pointer transition-colors duration-200" wire:click="toggleMetricVisibility('Más visitados')">
                            <div class="w-4 h-4 bg-white rounded-full absolute top-0.5 {{ !empty($enabledMetrics['Más visitados']) ? 'right-0.5' : 'left-0.5' }} shadow-sm transition-all duration-200"></div>
                        </div>
                        <div class="w-8 h-8 rounded bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[16px]">visibility</span>
                        </div>
                        <span class="text-sm font-bold text-gray-700">Más visitados</span>
                    </div>
                </div>
            </div>
            @endif

            @if($expandedMetric === 'usuarios')
            <div class="mt-6 border border-indigo-200 rounded bg-white shadow-sm animate-fade-in">
                <!-- Header -->
                <div class="flex items-center justify-between p-4 border-b border-indigo-50 bg-indigo-50/30">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-indigo-600">group</span>
                        <h3 class="text-sm font-bold text-gray-800 tracking-wider">USUARIOS</h3>
                        <span class="bg-indigo-50 text-indigo-700 text-[10px] font-semibold px-2 py-0.5 rounded">{{ $usuariosActivas }} / 2 activas</span>
                    </div>
                    <button wire:click="toggleMetric('usuarios')" class="flex items-center justify-center gap-1 bg-indigo-100 text-indigo-600 text-[11px] font-bold px-3 py-1 rounded hover:bg-indigo-200 transition-colors uppercase tracking-wider">
                        todo <span class="material-symbols-outlined text-[14px]">expand_less</span>
                    </button>
                </div>
                <!-- List -->
                <div class="divide-y divide-gray-50">
                    <!-- Item 1 -->
                    <div class="flex items-center gap-4 p-4 hover:bg-gray-50/50 transition-colors">
                        <div class="w-10 h-5 {{ !empty($enabledMetrics['Nuevos registros']) ? 'bg-indigo-600' : 'bg-gray-300' }} rounded-full relative cursor-pointer transition-colors duration-200" wire:click="toggleMetricVisibility('Nuevos registros')">
                            <div class="w-4 h-4 bg-white rounded-full absolute top-0.5 {{ !empty($enabledMetrics['Nuevos registros']) ? 'right-0.5' : 'left-0.5' }} shadow-sm transition-all duration-200"></div>
                        </div>
                        <div class="w-8 h-8 rounded bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[16px]">group_add</span>
                        </div>
                        <span class="text-sm font-bold text-gray-700">Nuevos registros</span>
                    </div>
                    <!-- Item 2 -->
                    <div class="flex items-center gap-4 p-4 hover:bg-gray-50/50 transition-colors">
                        <div class="w-10 h-5 {{ !empty($enabledMetrics['Clientes recurrentes']) ? 'bg-indigo-600' : 'bg-gray-300' }} rounded-full relative cursor-pointer transition-colors duration-200" wire:click="toggleMetricVisibility('Clientes recurrentes')">
                            <div class="w-4 h-4 bg-white rounded-full absolute top-0.5 {{ !empty($enabledMetrics['Clientes recurrentes']) ? 'right-0.5' : 'left-0.5' }} shadow-sm transition-all duration-200"></div>
                        </div>
                        <div class="w-8 h-8 rounded bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[16px]">sync</span>
                        </div>
                        <span class="text-sm font-bold text-gray-700">Clientes recurrentes</span>
                    </div>
                </div>
            </div>
            @endif
        </div>
    @elseif($activeTab === 'tienda')
        <div class="animate-fade-in">
            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2 mb-8">
                <span class="material-symbols-outlined text-indigo-600">storefront</span> Personalización de la Tienda
            </h2>

            <div class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 tracking-wide mb-2 uppercase">Nombre de la Tienda</label>
                        <input type="text" wire:model="storeName" placeholder="Shoply Demo" class="w-full border border-gray-200 rounded px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 tracking-wide mb-2 uppercase">Moneda de la Tienda</label>
                        <div class="relative">
                            <select wire:model="storeCurrency" class="w-full appearance-none border border-gray-200 rounded px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                                <option value="ARS">ARS ($)</option>
                                <option value="USD">USD ($)</option>
                                <option value="EUR">EUR (€)</option>
                                <option value="MXN">MXN ($)</option>
                                <option value="COP">COP ($)</option>
                                <option value="CLP">CLP ($)</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-2.5 text-gray-400 text-sm pointer-events-none">expand_more</span>
                        </div>
                    </div>
                </div>

                <div class="border border-gray-100 p-6 rounded bg-white shadow-sm flex flex-col md:flex-row md:items-center gap-6">
                    <div class="w-full md:w-64">
                        <label class="block text-xs font-bold text-gray-500 tracking-wide mb-2 uppercase">Estado de la cuenta / tienda</label>
                        <div class="relative">
                            <select wire:model="storeStatus" class="w-full appearance-none border border-gray-200 rounded px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                                <option value="active">Activa (Pública)</option>
                                <option value="maintenance">Mantenimiento</option>
                                <option value="suspended">Suspendida</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-2.5 text-gray-400 text-sm pointer-events-none">expand_more</span>
                        </div>
                    </div>
                    @if($storeStatus === 'active')
                    <div class="flex items-center gap-2 text-[#00897b] font-bold text-[11px] uppercase tracking-wide md:pt-6">
                        <span class="material-symbols-outlined text-[18px] bg-[#00897b] text-white rounded-full">check</span>
                        La tienda se encuentra activa y visible al público.
                    </div>
                    @elseif($storeStatus === 'maintenance')
                    <div class="flex items-center gap-2 text-orange-500 font-bold text-[11px] uppercase tracking-wide md:pt-6">
                        <span class="material-symbols-outlined text-[18px]">build</span>
                        La tienda está en mantenimiento.
                    </div>
                    @else
                    <div class="flex items-center gap-2 text-red-500 font-bold text-[11px] uppercase tracking-wide md:pt-6">
                        <span class="material-symbols-outlined text-[18px]">block</span>
                        La cuenta se encuentra suspendida.
                    </div>
                    @endif
                </div>

                <div class="border border-indigo-50/50 bg-[#faf5ff]/30 p-6 rounded shadow-sm">
                    <h3 class="flex items-center gap-2 text-indigo-700 font-bold text-[11px] uppercase tracking-wide mb-3">
                        <span class="material-symbols-outlined text-[16px]">schedule</span> Programar Ventana de Mantenimiento
                    </h3>
                    <p class="text-xs text-gray-500 mb-6 leading-relaxed">Si se configuran estas fechas, la tienda entrará en modo mantenimiento de forma automática entre el período definido, mostrando una cuenta regresiva para los clientes.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 tracking-wide mb-2 uppercase">Fecha / Hora de Inicio</label>
                            <input type="datetime-local" wire:model="maintenanceStart" class="w-full border border-gray-200 rounded px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500 text-gray-700">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 tracking-wide mb-2 uppercase">Fecha / Hora de Reapertura</label>
                            <input type="datetime-local" wire:model="maintenanceEnd" class="w-full border border-gray-200 rounded px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500 text-gray-700">
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="flex items-center gap-2 text-gray-600 font-bold text-[11px] uppercase tracking-wide mb-4">
                        <span class="material-symbols-outlined text-[16px]">info</span> Información de Contacto Pública
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 tracking-wide mb-2 uppercase">WhatsApp</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-[18px]">chat</span>
                                <input type="text" wire:model="whatsapp" placeholder="Ej. +54911223344" class="w-full border border-gray-200 rounded pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500 bg-gray-50/50">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 tracking-wide mb-2 uppercase">Instagram</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-[18px]">photo_camera</span>
                                <input type="text" wire:model="instagram" placeholder="Ej. mi.tienda" class="w-full border border-gray-200 rounded pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500 bg-gray-50/50">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 tracking-wide mb-2 uppercase">Email de Soporte</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-[18px]">mail</span>
                                <input type="email" wire:model="supportEmail" placeholder="Ej. soporte@mitienda.com" class="w-full border border-gray-200 rounded pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500 bg-gray-50/50">
                            </div>
                        </div>
                    </div>

                    <div class="mb-8 border-b border-gray-100 pb-8">
                        <label class="block text-[11px] font-bold text-gray-500 tracking-wide mb-3 uppercase">Logo de la Tienda</label>
                        <div class="flex items-center gap-6">
                            <div class="w-24 h-24 border border-dashed border-gray-300 bg-gray-50 rounded flex flex-col items-center justify-center text-gray-400 overflow-hidden relative">
                                @if ($storeLogo)
                                    <img src="{{ $storeLogo->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-contain bg-white">
                                @else
                                    <span class="material-symbols-outlined text-3xl mb-1">landscape</span>
                                    <span class="text-[10px] font-bold uppercase tracking-wider">Sin Logo</span>
                                @endif
                                <div wire:loading wire:target="storeLogo" class="absolute inset-0 bg-white/80 flex items-center justify-center z-10">
                                    <span class="material-symbols-outlined animate-spin text-indigo-600">progress_activity</span>
                                </div>
                            </div>
                            <div>
                                <label class="flex items-center gap-2 bg-[#f3e5f5] text-[#9c27b0] border border-[#e1bee7] px-4 py-2 rounded text-[11px] font-bold hover:bg-[#e1bee7]/50 transition-colors uppercase tracking-wide mb-2 cursor-pointer w-fit">
                                    <span class="material-symbols-outlined text-[16px]">cloud_upload</span> Seleccionar Imagen
                                    <input type="file" wire:model.live="storeLogo" class="hidden" accept="image/png, image/jpeg, image/webp">
                                </label>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wide">Formatos permitidos: PNG, JPG, WEBP. Máximo 2MB.</p>
                                @error('storeLogo') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <button wire:click="saveStoreConfig" class="bg-indigo-600 text-white flex items-center gap-2 px-6 py-3 rounded text-[11px] font-bold hover:bg-indigo-700 transition-colors uppercase tracking-wide shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">save</span> Guardar Configuración
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @elseif($activeTab === 'usuarios')
        <div class="animate-fade-in">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <span class="material-symbols-outlined text-indigo-600">group</span> Gestión de Usuarios del Sistema
                </h2>
                <button wire:click="openUserModal" class="bg-[#7c3aed] hover:bg-[#6d28d9] text-white flex items-center gap-2 px-5 py-2.5 rounded text-[11px] font-bold uppercase tracking-wide transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-[16px]">person_add</span> Crear Usuario
                </button>
            </div>

            <!-- Filters -->
            <div class="flex flex-col sm:flex-row gap-4 mb-6">
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-4 top-3 text-gray-400 text-[18px]">search</span>
                    <input type="text" wire:model.live="searchUser" placeholder="Buscar usuarios por nombre, email o DNI..." class="w-full border border-gray-200 rounded pl-12 pr-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500 bg-white">
                </div>
                <div class="relative w-full sm:w-64">
                    <select wire:model.live="roleFilter" class="w-full appearance-none border border-gray-200 rounded px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500 bg-white">
                        <option value="">Todos los Roles</option>
                        <option value="superadmin">Super Admin</option>
                        <option value="admin">Admin</option>
                        <option value="customer">Customer</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-2.5 text-gray-400 text-sm pointer-events-none">expand_more</span>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white border border-gray-200 rounded overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-gray-50/50 border-b border-gray-200 text-[10px] uppercase text-gray-500 tracking-wider font-bold">
                            <tr>
                                <th class="px-6 py-4">Usuario</th>
                                <th class="px-6 py-4">Email</th>
                                <th class="px-6 py-4">DNI</th>
                                <th class="px-6 py-4 text-center">Rol</th>
                                <th class="px-6 py-4 text-center">Estado</th>
                                <th class="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($this->users as $u)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <!-- Avatar Initials -->
                                            @php
                                                $initials = collect(explode(' ', $u->name))->map(fn($n) => substr($n, 0, 1))->take(2)->implode('');
                                                $isMe = auth()->id() === $u->id;
                                            @endphp
                                            <div class="w-8 h-8 rounded bg-indigo-50 text-indigo-700 font-bold text-xs flex items-center justify-center uppercase">
                                                {{ $initials }}
                                            </div>
                                            <div class="font-bold text-gray-800 flex items-center gap-2">
                                                {{ $u->name }}
                                                @if($isMe)
                                                    <span class="bg-[#f3e5f5] text-[#9c27b0] text-[10px] font-bold px-1.5 py-0.5 rounded">TÚ</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">{{ $u->email }}</td>
                                    <td class="px-6 py-4 text-gray-400">&mdash;</td>
                                    <td class="px-6 py-4 text-center">
                                        @php
                                            $roles = $u->getRoleNames();
                                            $mainRole = $roles->first() ?? 'CUSTOMER';
                                        @endphp
                                        @if(strtoupper($mainRole) === 'SUPERADMIN')
                                            <span class="bg-[#f3e5f5] text-[#9c27b0] text-[10px] font-bold px-2.5 py-1 rounded uppercase tracking-wider">{{ $mainRole }}</span>
                                        @elseif(strtoupper($mainRole) === 'ADMIN')
                                            <span class="bg-blue-50 text-blue-600 text-[10px] font-bold px-2.5 py-1 rounded uppercase tracking-wider">{{ $mainRole }}</span>
                                        @else
                                            <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-2.5 py-1 rounded uppercase tracking-wider">{{ $mainRole }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($isMe || strtoupper($mainRole) === 'SUPERADMIN')
                                            <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-500 text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider border border-gray-200">
                                                <span class="material-symbols-outlined text-[12px]">lock</span> PROTEGIDO
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-600 text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider border border-emerald-200">
                                                <span class="material-symbols-outlined text-[12px] font-bold">check_circle</span> ACTIVO
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button wire:click="editUser({{ $u->id }})" class="text-blue-600 hover:text-blue-800 transition-colors mx-1" title="Editar">
                                            <span class="material-symbols-outlined text-[18px]">edit_square</span>
                                        </button>
                                        @if(!$isMe && strtoupper($mainRole) !== 'SUPERADMIN')
                                        <button wire:click="deleteUser({{ $u->id }})" wire:confirm="¿Estás seguro de eliminar este usuario?" class="text-red-500 hover:text-red-700 transition-colors mx-1" title="Eliminar">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                        @else
                                        <button class="text-gray-300 mx-1 cursor-not-allowed" title="No se puede eliminar" disabled>
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                        <span class="material-symbols-outlined text-4xl mb-2 opacity-50">group_off</span>
                                        <p>No se encontraron usuarios que coincidan con la búsqueda.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @elseif($activeTab === 'limites')
        <div class="animate-fade-in">
            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2 mb-8">
                <span class="material-symbols-outlined text-indigo-600">trending_up</span> Límites de Uso y Facturación de Suscripción
            </h2>

            <div class="space-y-8">
                <!-- Sección 1 -->
                <div>
                    <h3 class="flex items-center gap-2 text-gray-600 font-bold text-[11px] uppercase tracking-wide mb-4">
                        <span class="material-symbols-outlined text-[16px]">speed</span> Cuotas y Límites de Recursos
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 tracking-wide mb-2 uppercase">Límite de Productos</label>
                            <input type="number" wire:model="limitProducts" class="w-full border border-gray-200 rounded px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                            @error('limitProducts') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 tracking-wide mb-2 uppercase">Límite de Usuarios</label>
                            <input type="number" wire:model="limitUsers" class="w-full border border-gray-200 rounded px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                            @error('limitUsers') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 tracking-wide mb-2 uppercase">Límite de Pedidos / Mes</label>
                            <input type="number" wire:model="limitOrders" class="w-full border border-gray-200 rounded px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                            @error('limitOrders') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100">

                <!-- Sección 2 -->
                <div>
                    <h3 class="flex items-center gap-2 text-gray-600 font-bold text-[11px] uppercase tracking-wide mb-4">
                        <span class="material-symbols-outlined text-[16px]">credit_card</span> Detalles de Facturación y Suscripción
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 tracking-wide mb-2 uppercase">Plan de Suscripción</label>
                            <div class="relative">
                                <select wire:model.live="planId" class="w-full appearance-none border border-gray-200 rounded px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500 bg-white">
                                    <option value="">Personalizado</option>
                                    @foreach(config('plans', []) as $p)
                                        <option value="{{ $p['id'] }}">{{ $p['name'] }}</option>
                                    @endforeach
                                </select>
                                <span class="material-symbols-outlined absolute right-3 top-2.5 text-gray-400 text-sm pointer-events-none">expand_more</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 tracking-wide mb-2 uppercase">Precio del Plan</label>
                            <div class="relative">
                                <span class="absolute left-4 top-2.5 text-gray-400 text-sm font-bold">$</span>
                                <input type="number" step="0.01" wire:model="planPrice" placeholder="0.00" class="w-full border border-gray-200 rounded pl-8 pr-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                            </div>
                            @error('planPrice') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 tracking-wide mb-2 uppercase">Fecha Próximo Vencimiento</label>
                            <input type="date" wire:model="planDueDate" class="w-full border border-gray-200 rounded px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500 text-gray-700">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 tracking-wide mb-2 uppercase">Ciclo de Facturación</label>
                            <div class="relative">
                                <select wire:model="planBillingCycle" class="w-full appearance-none border border-gray-200 rounded px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500 bg-white">
                                    <option value="mensual">Mensual</option>
                                    <option value="trimestral">Trimestral</option>
                                    <option value="semestral">Semestral</option>
                                    <option value="anual">Anual</option>
                                </select>
                                <span class="material-symbols-outlined absolute right-3 top-2.5 text-gray-400 text-sm pointer-events-none">expand_more</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button wire:click="saveLimitsConfig" class="bg-[#7c3aed] text-white flex items-center gap-2 px-6 py-3 rounded text-[11px] font-bold hover:bg-[#6d28d9] transition-colors uppercase tracking-wide shadow-sm">
                        <span class="material-symbols-outlined text-[16px]">save</span> Guardar Límites y Plan
                    </button>
                </div>
            </div>
        </div>
    @elseif($activeTab === 'auditoria')
        <div class="animate-fade-in">
            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2 mb-6">
                <span class="material-symbols-outlined text-indigo-600">history</span> Historial de Actividad (Auditoría)
            </h2>

            <div class="bg-white border border-gray-200 rounded overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-gray-50/50 border-b border-gray-200 text-[10px] uppercase text-gray-400 tracking-wider font-bold">
                            <tr>
                                <th class="px-6 py-4">Fecha / Hora</th>
                                <th class="px-6 py-4">Actor</th>
                                <th class="px-6 py-4">Acción</th>
                                <th class="px-6 py-4">Descripción</th>
                                <th class="px-6 py-4 text-center">Detalles</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($this->auditLogs as $log)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 text-gray-500 text-xs">{{ $log['date'] }}</td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-800 text-xs">{{ $log['actor'] }}</div>
                                        <div class="text-[10px] text-gray-400">{{ $log['email'] }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="bg-gray-100 text-gray-600 text-[9px] font-bold px-2 py-1 rounded uppercase tracking-wider">{{ $log['action'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 text-xs">{{ $log['description'] }}</td>
                                    <td class="px-6 py-4 text-center text-gray-300">{{ $log['details'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                        <span class="material-symbols-outlined text-4xl mb-2 opacity-50">history_off</span>
                                        <p>No hay registros de actividad aún.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="py-12 text-center text-gray-400 text-sm">
            Contenido de {{ ucfirst($activeTab) }} (En desarrollo)
        </div>
    @endif

    <!-- User Modal -->
    @if($isUserModalOpen)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center animate-fade-in p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md overflow-hidden" @click.away="$wire.closeUserModal()">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">
                        {{ $editingUserId ? 'Editar Usuario' : 'Crear Usuario' }}
                    </h3>
                    <button wire:click="closeUserModal" class="text-gray-400 hover:text-gray-600">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                
                <form wire:submit="saveUser" class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Nombre</label>
                        <input type="text" wire:model="userName" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-[#7c3aed]">
                        @error('userName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Email</label>
                        <input type="email" wire:model="userEmail" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-[#7c3aed]">
                        @error('userEmail') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Contraseña {{ $editingUserId ? '(Opcional)' : '' }}</label>
                        <input type="password" wire:model="userPassword" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-[#7c3aed]">
                        @error('userPassword') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Rol</label>
                        <select wire:model="userRole" class="w-full border border-gray-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-[#7c3aed] bg-white">
                            <option value="customer">Customer</option>
                            <option value="admin">Admin</option>
                            <option value="superadmin">Super Admin</option>
                        </select>
                        @error('userRole') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="pt-4 flex justify-end gap-3">
                        <button type="button" wire:click="closeUserModal" class="px-4 py-2 text-sm font-bold text-gray-600 hover:text-gray-900 uppercase tracking-wide">
                            Cancelar
                        </button>
                        <button type="submit" class="bg-[#7c3aed] hover:bg-[#6d28d9] text-white px-5 py-2 rounded text-sm font-bold uppercase tracking-wide transition-colors">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Plan Semilla -->
    @if($showSemillaPlanModal)
    <div class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/50 backdrop-blur-sm animate-fade-in p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-8 text-center relative">
            <div class="w-20 h-20 mx-auto rounded-full border-[3px] border-[#00b0ff] flex items-center justify-center mb-6 bg-[#00b0ff]/5">
                <span class="text-[#00b0ff] text-[40px] font-bold font-serif italic">i</span>
            </div>
            
            <h2 class="text-2xl font-bold text-gray-800 mb-4">¿Aplicar Plan Semilla?</h2>
            
            <p class="text-[13px] text-gray-600 mb-8 leading-relaxed px-4">
                Esto habilitará únicamente los módulos y métricas<br>
                esenciales del sistema (Catálogo, Órdenes, Portadas,<br>
                Opciones y KPIs básicos).
            </p>
            
            <div class="flex items-center justify-center gap-3">
                <button wire:click="applySemillaPlan" class="px-6 py-2.5 bg-[#f59e0b] hover:bg-[#d97706] text-white font-bold text-sm rounded transition-colors shadow-sm">
                    Sí, aplicar Plan Semilla
                </button>
                <button wire:click="$set('showSemillaPlanModal', false)" class="px-6 py-2.5 bg-[#636e72] hover:bg-[#2d3436] text-white font-bold text-sm rounded transition-colors shadow-sm">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Plan Básico (Flor) -->
    @if($showBasicPlanModal)
    <div class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/50 backdrop-blur-sm animate-fade-in p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-8 text-center relative">
            <div class="w-20 h-20 mx-auto rounded-full border-[3px] border-[#00b0ff] flex items-center justify-center mb-6 bg-[#00b0ff]/5">
                <span class="text-[#00b0ff] text-[40px] font-bold font-serif italic">i</span>
            </div>
            
            <h2 class="text-2xl font-bold text-gray-800 mb-4">¿Aplicar Plan Básico?</h2>
            
            <p class="text-[13px] text-gray-600 mb-8 leading-relaxed px-4">
                Esto habilitará únicamente los módulos y métricas<br>
                esenciales del sistema (Catálogo, Órdenes, Portadas,<br>
                Opciones y KPIs básicos).
            </p>
            
            <div class="flex items-center justify-center gap-3">
                <button wire:click="applyBasicPlan" class="px-6 py-2.5 bg-[#e67e22] hover:bg-[#d35400] text-white font-bold text-sm rounded transition-colors shadow-sm">
                    Sí, aplicar Plan Básico
                </button>
                <button wire:click="$set('showBasicPlanModal', false)" class="px-6 py-2.5 bg-[#636e72] hover:bg-[#2d3436] text-white font-bold text-sm rounded transition-colors shadow-sm">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Plan Premium (Extracto) -->
    @if($showPremiumPlanModal)
    <div class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/50 backdrop-blur-sm animate-fade-in p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-8 text-center relative">
            <div class="w-20 h-20 mx-auto rounded-full border-[3px] border-[#2ecc71] flex items-center justify-center mb-6 bg-[#2ecc71]/5">
                <span class="material-symbols-outlined text-[#2ecc71] text-[40px] font-bold">check</span>
            </div>
            
            <h2 class="text-2xl font-bold text-gray-800 mb-4">¿Aplicar Plan Premium?</h2>
            
            <p class="text-[13px] text-gray-600 mb-8 leading-relaxed px-4">
                Esto habilitará absolutamente todos los módulos y<br>
                métricas del sistema.
            </p>
            
            <div class="flex items-center justify-center gap-3">
                <button wire:click="applyPremiumPlan" class="px-6 py-2.5 bg-[#8b5cf6] hover:bg-[#7c3aed] text-white font-bold text-sm rounded transition-colors shadow-sm">
                    Sí, aplicar Plan Premium
                </button>
                <button wire:click="$set('showPremiumPlanModal', false)" class="px-6 py-2.5 bg-[#636e72] hover:bg-[#2d3436] text-white font-bold text-sm rounded transition-colors shadow-sm">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
