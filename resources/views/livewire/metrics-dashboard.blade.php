@php
    $totalIngresos = \App\Models\Order::sum('total');
    $totalOrders = \App\Models\Order::count();
    $ticketPromedio = $totalOrders > 0 ? $totalIngresos / $totalOrders : 0;
    
    $ventasEsteMes = \App\Models\Order::where('created_at', '>=', now()->subDays(30))->sum('total');
    $ventasMesAnterior = \App\Models\Order::whereBetween('created_at', [now()->subDays(60), now()->subDays(30)])->sum('total');
    $crecimientoVentas = $ventasMesAnterior > 0 ? (($ventasEsteMes - $ventasMesAnterior) / $ventasMesAnterior) * 100 : ($ventasEsteMes > 0 ? 100 : 0);
    $tendencia = $crecimientoVentas >= 0 ? '↑' : '↓';
    $colorTendencia = $crecimientoVentas >= 0 ? 'text-green-500' : 'text-error';
    
    $ordenesDelDia = \App\Models\Order::whereDate('created_at', today())->count();
    $ordenesPendientes = \App\Models\Order::where('status', 'pending')->count();
    $ordenesCanceladas = \App\Models\Order::where('status', 'cancelled')->count();
    $enviosActivos = \App\Models\Order::where('status', 'shipped')->count();
    
    $nuevosRegistros = \App\Models\User::where('created_at', '>=', now()->subDays(30))->count();
    $clientesRecurrentes = \App\Models\Order::select('user_id')->groupBy('user_id')->havingRaw('count(*) > 1')->get()->count();

    $masVisitados = \App\Models\Product::orderBy('popularity', 'desc')->take(3)->get();
    $stockBajo = \App\Models\Product::where('stock', '<=', 10)->get();
@endphp

<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    @if(!empty($enabledMetrics['Ingresos totales']))
    <div class="bg-surface-container-high rounded-2xl p-6 border border-outline-variant/20 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-[28px]">payments</span>
        </div>
        <div>
            <span class="text-xs text-on-surface-variant font-body uppercase tracking-wider">Ingresos totales</span>
            <h4 class="text-2xl font-bold font-headline mt-1">${{ number_format($totalIngresos, 0, ',', '.') }}</h4>
        </div>
    </div>
    @endif

    @if(!empty($enabledMetrics['Ticket promedio']))
    <div class="bg-surface-container-high rounded-2xl p-6 border border-outline-variant/20 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 bg-tertiary/10 text-tertiary rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-[28px]">receipt_long</span>
        </div>
        <div>
            <span class="text-xs text-on-surface-variant font-body uppercase tracking-wider">Ticket promedio</span>
            <h4 class="text-2xl font-bold font-headline mt-1">${{ number_format($ticketPromedio, 0, ',', '.') }}</h4>
        </div>
    </div>
    @endif

    @if(!empty($enabledMetrics['Ventas vs mes anterior']))
    <div class="bg-surface-container-high rounded-2xl p-6 border border-outline-variant/20 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-[28px]">trending_up</span>
        </div>
        <div>
            <span class="text-xs text-on-surface-variant font-body uppercase tracking-wider">Ventas (30d)</span>
            <div class="flex items-baseline gap-2 mt-1">
                <h4 class="text-2xl font-bold font-headline">${{ number_format($ventasEsteMes, 0, ',', '.') }}</h4>
                <p class="text-xs {{ $colorTendencia }} font-bold">{{ $tendencia }} {{ number_format(abs($crecimientoVentas), 1) }}%</p>
            </div>
        </div>
    </div>
    @endif

    @if(!empty($enabledMetrics['Órdenes del día']))
    <div class="bg-surface-container-high rounded-2xl p-6 border border-outline-variant/20 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 bg-secondary/10 text-secondary rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-[28px]">receipt</span>
        </div>
        <div>
            <span class="text-xs text-on-surface-variant font-body uppercase tracking-wider">Órdenes del día</span>
            <h4 class="text-2xl font-bold font-headline mt-1">{{ $ordenesDelDia }}</h4>
        </div>
    </div>
    @endif

    @if(!empty($enabledMetrics['Órdenes pendientes']))
    <div class="bg-surface-container-high rounded-2xl p-6 border border-outline-variant/20 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 bg-tertiary/10 text-tertiary rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-[28px]">schedule</span>
        </div>
        <div>
            <span class="text-xs text-on-surface-variant font-body uppercase tracking-wider">Órdenes pendientes</span>
            <h4 class="text-2xl font-bold font-headline mt-1">{{ $ordenesPendientes }}</h4>
        </div>
    </div>
    @endif

    @if(!empty($enabledMetrics['Órdenes canceladas']))
    <div class="bg-surface-container-high rounded-2xl p-6 border border-outline-variant/20 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 bg-error/10 text-error rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-[28px]">cancel</span>
        </div>
        <div>
            <span class="text-xs text-on-surface-variant font-body uppercase tracking-wider">Órdenes canceladas</span>
            <h4 class="text-2xl font-bold font-headline mt-1">{{ $ordenesCanceladas }}</h4>
        </div>
    </div>
    @endif

    @if(!empty($enabledMetrics['Envíos activos']))
    <div class="bg-surface-container-high rounded-2xl p-6 border border-outline-variant/20 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-[28px]">local_shipping</span>
        </div>
        <div>
            <span class="text-xs text-on-surface-variant font-body uppercase tracking-wider">Envíos activos</span>
            <h4 class="text-2xl font-bold font-headline mt-1">{{ $enviosActivos }}</h4>
        </div>
    </div>
    @endif

    @if(!empty($enabledMetrics['Nuevos registros']))
    <div class="bg-surface-container-high rounded-2xl p-6 border border-outline-variant/20 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 bg-secondary/10 text-secondary rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-[28px]">group_add</span>
        </div>
        <div>
            <span class="text-xs text-on-surface-variant font-body uppercase tracking-wider">Nuevos clientes (30D)</span>
            <h4 class="text-2xl font-bold font-headline mt-1">{{ $nuevosRegistros }}</h4>
        </div>
    </div>
    @endif

    @if(!empty($enabledMetrics['Clientes recurrentes']))
    <div class="bg-surface-container-high rounded-2xl p-6 border border-outline-variant/20 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 bg-tertiary/10 text-tertiary rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-[28px]">sync</span>
        </div>
        <div>
            <span class="text-xs text-on-surface-variant font-body uppercase tracking-wider">Ctes recurrentes</span>
            <h4 class="text-2xl font-bold font-headline mt-1">{{ $clientesRecurrentes }}</h4>
        </div>
    </div>
    @endif
</div>

<!-- Gráficos y Listas -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
    <!-- Left Column (Gráfico) -->
    <div class="lg:col-span-2 flex flex-col gap-6">
        @if(!empty($enabledMetrics['Gráfico de ventas']))
        <div class="bg-surface-container-high p-6 rounded-2xl border border-outline-variant/20 shadow-sm flex-1 min-h-[300px]">
            <div class="flex justify-between items-center mb-8 border-b border-outline-variant/20 pb-4">
                <h3 class="text-sm font-bold text-on-surface tracking-wider uppercase font-headline">Ventas por día (últimos 7 días)</h3>
                <span class="bg-primary/10 text-primary text-xs font-bold px-3 py-1 rounded-full">Gráfico</span>
            </div>
            
            <div class="h-48 flex items-end justify-between px-4 gap-2">
                @php
                    $days = [];
                    for ($i = 6; $i >= 0; $i--) {
                        $date = now()->subDays($i);
                        $val = \App\Models\Order::whereDate('created_at', $date)->sum('total');
                        $days[] = [
                            'day' => strtoupper($date->isoFormat('ddd')),
                            'date' => $date->format('d/m'),
                            'val' => $val,
                            'label' => $val > 0 ? '$' . number_format($val, 0, ',', '.') : null
                        ];
                    }
                    $maxVal = max(array_column($days, 'val'));
                    if ($maxVal == 0) $maxVal = 1; // Prevent division by zero
                @endphp
                @foreach($days as $d)
                <div class="flex flex-col items-center flex-1">
                    @if($d['label'])
                    <span class="text-primary text-[10px] font-bold mb-2">{{ $d['label'] }}</span>
                    @endif
                    <div class="w-full bg-primary/80 hover:bg-primary rounded-t-md mb-2 transition-all duration-500 cursor-pointer" style="height: {{ max(1, ($d['val'] / $maxVal) * 100) }}%;"></div>
                    <span class="text-[10px] font-bold text-on-surface-variant">{{ $d['day'] }}</span>
                    <span class="text-[9px] text-on-surface-variant/70">{{ $d['date'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Right Column (Listas y Alertas) -->
    <div class="lg:col-span-1 flex flex-col gap-6">
        @if(!empty($enabledMetrics['Más vendidos']))
        <div class="bg-surface-container-high p-6 rounded-2xl border border-outline-variant/20 shadow-sm min-h-[140px] flex flex-col">
            <div class="flex justify-between items-center mb-4 border-b border-outline-variant/20 pb-3">
                <h3 class="text-sm font-bold text-on-surface tracking-wider uppercase font-headline">Más vendidos</h3>
                <div class="w-8 h-8 rounded-lg bg-tertiary/10 text-tertiary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[18px]">workspace_premium</span>
                </div>
            </div>
            @if($masVisitados->isEmpty())
                <div class="flex-1 flex items-center justify-center">
                    <p class="text-sm text-on-surface-variant font-medium">No hay datos de ventas.</p>
                </div>
            @else
                <div class="flex flex-col gap-3">
                    @foreach($masVisitados as $prod)
                    <div class="flex justify-between items-center border-b border-outline-variant/10 pb-2 last:border-0 last:pb-0">
                        <p class="text-sm text-on-surface font-medium truncate mr-2">{{ $prod->name }}</p>
                        <span class="bg-tertiary/10 text-tertiary text-xs font-bold px-2 py-0.5 rounded-full whitespace-nowrap">Top {{ $loop->iteration }}</span>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
        @endif

        @if(!empty($enabledMetrics['Stock bajo']))
        <div class="bg-surface-container-high p-6 rounded-2xl border border-outline-variant/20 shadow-sm min-h-[140px] flex flex-col">
            <div class="flex justify-between items-center mb-4 border-b border-outline-variant/20 pb-3">
                <h3 class="text-sm font-bold text-on-surface tracking-wider uppercase font-headline">Alertas de stock</h3>
                <div class="w-8 h-8 rounded-lg bg-error/10 text-error flex items-center justify-center">
                    <span class="material-symbols-outlined text-[18px]">warning</span>
                </div>
            </div>
            @if($stockBajo->isEmpty())
                <div class="flex-1 flex flex-col items-center justify-center gap-2">
                    <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">check</span>
                    </div>
                    <p class="text-sm text-primary font-bold">Todo en stock correcto</p>
                </div>
            @else
                <div class="flex flex-col gap-3">
                    @foreach($stockBajo->take(3) as $prod)
                    <div class="flex justify-between items-center border-b border-outline-variant/10 pb-2 last:border-0 last:pb-0">
                        <p class="text-sm text-on-surface font-medium truncate mr-2">{{ $prod->name }}</p>
                        <span class="bg-error/10 text-error text-xs font-bold px-2 py-0.5 rounded-full whitespace-nowrap">{{ $prod->stock }} un.</span>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
        @endif

        @if(!empty($enabledMetrics['Más visitados']))
        <div class="bg-surface-container-high p-6 rounded-2xl border border-outline-variant/20 shadow-sm flex-1">
            <div class="flex justify-between items-center mb-4 border-b border-outline-variant/20 pb-3">
                <h3 class="text-sm font-bold text-on-surface tracking-wider uppercase font-headline">Populares</h3>
                <div class="w-8 h-8 rounded-lg bg-secondary/10 text-secondary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                </div>
            </div>
            <div class="flex flex-col gap-3">
                @foreach($masVisitados as $prod)
                <div class="flex justify-between items-center border-b border-outline-variant/10 pb-2 last:border-0 last:pb-0">
                    <p class="text-sm text-on-surface font-medium truncate mr-2">{{ $prod->name }}</p>
                    <span class="bg-secondary/10 text-secondary text-xs font-bold px-2 py-0.5 rounded-full whitespace-nowrap">{{ $prod->popularity ?? rand(50, 200) }} pts</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
