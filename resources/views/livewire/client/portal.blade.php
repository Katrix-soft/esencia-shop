<div class="w-full bg-background min-h-screen py-12 px-6 max-w-7xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Sidebar Navigation -->
        <aside class="lg:col-span-3">
            <div class="bg-surface-container-low border border-outline-variant/20 rounded-2xl p-6 sticky top-28 shadow-sm">
                <!-- User Head -->
                <div class="flex items-center gap-4 mb-8 border-b border-outline-variant/25 pb-6">
                    <div class="w-14 h-14 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xl font-headline shadow-inner">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div>
                        <h2 class="font-bold text-lg text-on-surface font-headline leading-tight">{{ auth()->user()->name }}</h2>
                        <span class="text-xs text-on-surface-variant font-body block mt-0.5">Cliente Esencia</span>
                    </div>
                </div>

                <!-- Nav links -->
                <nav class="space-y-1">
                    @if(cache('club_cologne_enabled', true) && cache('plan_id', '') !== 'toilette')
                    <button wire:click="switchSection('fidelity')" class="w-full text-left px-4 py-3 rounded-xl text-xs font-bold font-body transition-all flex items-center gap-3 {{ $activeSection === 'fidelity' ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:text-primary hover:bg-secondary-container' }}">
                        <span class="material-symbols-outlined text-[18px]">verified</span>
                        Club de Cologne (Fidelidad)
                    </button>
                    @endif
                    <button wire:click="switchSection('profile_dna')" class="w-full text-left px-4 py-3 rounded-xl text-xs font-bold font-body transition-all flex items-center gap-3 {{ $activeSection === 'profile_dna' ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:text-primary hover:bg-secondary-container' }}">
                        <span class="material-symbols-outlined text-[18px]">spa</span>
                        Mi ADN Olfativo (IA)
                    </button>
                    <button wire:click="switchSection('orders')" class="w-full text-left px-4 py-3 rounded-xl text-xs font-bold font-body transition-all flex items-center gap-3 {{ $activeSection === 'orders' ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:text-primary hover:bg-secondary-container' }}">
                        <span class="material-symbols-outlined text-[18px]">local_shipping</span>
                        Mis Compras
                    </button>
                    <button wire:click="switchSection('settings')" class="w-full text-left px-4 py-3 rounded-xl text-xs font-bold font-body transition-all flex items-center gap-3 {{ $activeSection === 'settings' ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:text-primary hover:bg-secondary-container' }}">
                        <span class="material-symbols-outlined text-[18px]">settings</span>
                        Ajustes de Perfil
                    </button>
                </nav>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="lg:col-span-9">
            <!-- Section 1: Fidelity / Club Semillas -->
            @if($activeSection === 'fidelity' && cache('club_cologne_enabled', true) && cache('plan_id', '') !== 'toilette')
                <div class="space-y-8 animate-fade-in">
                    <!-- Head -->
                    <div>
                        <h1 class="text-3xl font-headline font-bold text-on-surface">Club de Cologne</h1>
                        <p class="text-on-surface-variant font-body mt-1 text-sm">Acumula puntos con cada compra y desbloquea beneficios VIP.</p>
                    </div>

                    <!-- Card & Progress -->
                    @php
                        $level = $this->fidelityLevel;
                    @endphp
                    <div class="bg-gradient-to-br {{ $level['color'] }} text-white rounded-3xl p-8 shadow-lg relative overflow-hidden">
                        <!-- Abstract Scent lines decoration -->
                        <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none">
                            <span class="material-symbols-outlined text-[300px]">spa</span>
                        </div>
                        
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative z-10">
                            <div>
                                <span class="text-[10px] font-bold tracking-wider uppercase bg-white/20 px-3 py-1 rounded-full">Nivel {{ $level['name'] }}</span>
                                <h3 class="text-4xl font-headline font-bold mt-3">{{ $this->semillas }} <span class="text-lg font-body font-normal">Semillas</span></h3>
                                <p class="text-xs text-white/80 font-body mt-1.5">Semillas válidas hasta Diciembre 2026</p>
                            </div>
                            
                            @if($level['points_needed'] > 0)
                                <div class="bg-black/15 backdrop-blur-md p-5 rounded-2xl border border-white/10 w-full md:w-80">
                                    <div class="flex justify-between text-xs font-body mb-2">
                                        <span>Progreso a Nivel {{ $level['next'] }}</span>
                                        <span class="font-bold">{{ round($level['progress']) }}%</span>
                                    </div>
                                    <!-- Progress Bar -->
                                    <div class="w-full bg-white/25 rounded-full h-2">
                                        <div class="bg-white h-2 rounded-full transition-all duration-500" style="width: {{ $level['progress'] }}%"></div>
                                    </div>
                                    <span class="text-[10px] text-white/90 font-body mt-2 block">Te faltan {{ $level['points_needed'] }} semillas para subir de nivel.</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Perks Grid -->
                    <div class="bg-surface-container-low border border-outline-variant/20 rounded-2xl p-8">
                        <h3 class="text-lg font-bold font-headline mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">redeem</span>
                            Tus Beneficios Activos (Nivel {{ $level['name'] }})
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($level['perks'] as $perk)
                                <div class="bg-surface border border-outline-variant/15 p-4 rounded-xl flex items-start gap-3">
                                    <span class="material-symbols-outlined text-primary text-[20px] mt-0.5">check_circle</span>
                                    <span class="text-xs text-on-surface font-body leading-relaxed">{{ $perk }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- How to earn -->
                    <div class="bg-surface-container-low border border-outline-variant/20 rounded-2xl p-8">
                        <h3 class="text-lg font-bold font-headline mb-6">¿Cómo acumular semillas?</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                            <div class="space-y-2">
                                <div class="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center mx-auto">
                                    <span class="material-symbols-outlined text-[24px]">shopping_cart</span>
                                </div>
                                <h4 class="font-bold text-xs font-headline">Por Comprar</h4>
                                <p class="text-[11px] text-on-surface-variant font-body leading-relaxed">Suma 1 semilla por cada $100 en todas tus compras de perfumes.</p>
                            </div>
                            <div class="space-y-2">
                                <div class="w-12 h-12 rounded-full bg-tertiary/10 text-tertiary flex items-center justify-center mx-auto">
                                    <span class="material-symbols-outlined text-[24px]">science</span>
                                </div>
                                <h4 class="font-bold text-xs font-headline">Perfil Olfativo</h4>
                                <p class="text-[11px] text-on-surface-variant font-body leading-relaxed">Completa el test de IA y recibe 100 semillas de bienvenida.</p>
                            </div>
                            <div class="space-y-2">
                                <div class="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center mx-auto">
                                    <span class="material-symbols-outlined text-[24px]">share</span>
                                </div>
                                <h4 class="font-bold text-xs font-headline">Recomendar amigos</h4>
                                <p class="text-[11px] text-on-surface-variant font-body leading-relaxed">Invita a un amigo a hacer su test y recibe 150 semillas al realizar su primer pedido.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Section 2: DNA / Mi ADN Olfativo (Dashboard Estilo CRM) -->
            @if($activeSection === 'profile_dna')
                <div class="space-y-6 animate-fade-in bg-[#fdfaf5] p-6 sm:p-8 rounded-3xl shadow-sm border border-[#e8dfce]">
                    
                    <!-- Customer Header Info -->
                    <div class="flex flex-col md:flex-row justify-between items-start gap-6 border-b border-[#e8dfce] pb-6 mb-2">
                        <div class="flex gap-5 items-center">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=2f5a43&color=fff&size=128" alt="Profile" class="w-24 h-24 rounded-full object-cover shadow-sm border-2 border-white">
                            <div>
                                <h1 class="text-3xl font-headline font-bold text-[#2e3230] uppercase tracking-wide">{{ auth()->user()->name }}</h1>
                                <div class="flex flex-wrap items-center gap-4 text-sm text-[#5f6360] font-body mt-2">
                                    <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px]">mail</span> {{ auth()->user()->email }}</span>
                                    @if(auth()->user()->location || auth()->user()->postal_code)
                                        <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px]">location_on</span> {{ auth()->user()->location }}{{ auth()->user()->postal_code ? ' (' . auth()->user()->postal_code . ')' : '' }}</span>
                                    @endif
                                    @if(auth()->user()->phone)
                                        <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px]">call</span> {{ auth()->user()->phone }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @php
                        $dna = $this->olfactiveProfile;
                        $timeline = $this->timeline;
                        $ai = $this->aiRecommendation;
                    @endphp
                    
                    <!-- 3 Column Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        
                        <!-- Col 1: Perfil Olfativo (Circle Chart) -->
                        <div class="bg-white border border-[#e8dfce] rounded-2xl p-6 shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
                            <h3 class="text-sm font-headline font-bold text-[#2e3230] mb-6">Perfil Olfativo (Preferencias)</h3>
                            
                            <!-- Circle Chart Representation (Kept as requested) -->
                            <div class="relative w-40 h-40 mx-auto flex items-center justify-center mb-6">
                                <svg class="w-full h-full -rotate-90" viewBox="0 0 42 42">
                                    <circle cx="21" cy="21" r="15.915" fill="transparent" stroke="#f0e6d2" stroke-width="4"></circle>
                                    
                                    <!-- Wood Segment -->
                                    <circle cx="21" cy="21" r="15.915" fill="transparent" stroke="#2f5a43" stroke-width="4" 
                                            stroke-dasharray="{{ $dna['wood'] }} {{ 100 - $dna['wood'] }}" 
                                            stroke-dashoffset="100"></circle>
                                    
                                    <!-- Citrus Segment -->
                                    <circle cx="21" cy="21" r="15.915" fill="transparent" stroke="#8a6e30" stroke-width="4" 
                                            stroke-dasharray="{{ $dna['citrus'] }} {{ 100 - $dna['citrus'] }}" 
                                            stroke-dashoffset="{{ 100 - $dna['wood'] }}"></circle>
                                    
                                    <!-- Floral Segment -->
                                    <circle cx="21" cy="21" r="15.915" fill="transparent" stroke="#dcc48e" stroke-width="4" 
                                            stroke-dasharray="{{ $dna['floral'] }} {{ 100 - $dna['floral'] }}" 
                                            stroke-dashoffset="{{ 100 - $dna['wood'] - $dna['citrus'] }}"></circle>
                                </svg>
                                <div class="absolute inset-0 flex flex-col items-center justify-center">
                                    <span class="text-xl font-headline font-bold text-[#2f5a43] text-center leading-tight px-4">{{ $dna['label'] }}</span>
                                </div>
                            </div>

                            <div class="space-y-3 w-full text-xs font-body mt-4">
                                <div class="flex justify-between items-center bg-surface-container-lowest p-2 rounded-lg">
                                    <span class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-[#2f5a43]"></div> Amaderados</span>
                                    <span class="font-bold">{{ $dna['wood'] }}%</span>
                                </div>
                                <div class="flex justify-between items-center bg-surface-container-lowest p-2 rounded-lg">
                                    <span class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-[#8a6e30]"></div> Cítricos</span>
                                    <span class="font-bold">{{ $dna['citrus'] }}%</span>
                                </div>
                                <div class="flex justify-between items-center bg-surface-container-lowest p-2 rounded-lg">
                                    <span class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-[#dcc48e]"></div> Florales/Otros</span>
                                    <span class="font-bold">{{ $dna['floral'] }}%</span>
                                </div>
                            </div>
                        </div>

                        <!-- Col 2: Historial de Compras (Timeline) -->
                        <div class="bg-white border border-[#e8dfce] rounded-2xl p-6 shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
                            <h3 class="text-sm font-headline font-bold text-[#2e3230] mb-6">Actividad Reciente (Timeline)</h3>
                            <div class="relative border-l border-[#e8dfce] ml-3 space-y-8 pb-4">
                                @foreach($timeline as $event)
                                    <div class="relative pl-6">
                                        <!-- Node -->
                                        <div class="absolute -left-1.5 top-1.5 w-3 h-3 rounded-full {{ $event['color'] }} border-2 border-white shadow-sm"></div>
                                        <div class="flex justify-between items-start gap-2">
                                            <div>
                                                <span class="text-xs text-[#5f6360] font-body">{{ $event['date'] }}</span>
                                                <h4 class="font-bold text-sm text-[#2e3230] font-headline mt-1">{{ $event['type'] }}</h4>
                                                <p class="text-xs text-[#5f6360] mt-0.5">{{ $event['title'] }}</p>
                                            </div>
                                            @if($event['amount'])
                                                <span class="font-bold text-sm text-[#2e3230]">{{ $event['amount'] }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Col 3: Recomendación IA -->
                        <div class="relative group">
                            <!-- Glow Effect -->
                            <div class="absolute inset-0 bg-[#2f5a43]/20 blur-xl rounded-2xl group-hover:bg-[#2f5a43]/30 transition-all duration-500"></div>
                            
                            <div class="bg-[#dcf5e3] border-2 border-[#81c784] rounded-2xl p-6 relative h-full flex flex-col items-center text-center shadow-[0_8px_30px_rgba(47,90,67,0.15)] z-10">
                                <h3 class="text-sm font-headline font-bold text-[#1b3b2b] uppercase tracking-widest w-full text-left mb-6">Recomendación IA</h3>
                                
                                @if($ai)
                                    <div class="w-32 h-32 mb-4 bg-white/50 rounded-xl p-2 shadow-sm border border-white/60">
                                        <img src="{{ $ai['product']['image'] }}" alt="{{ $ai['product']['name'] }}" class="w-full h-full object-contain mix-blend-multiply drop-shadow-md">
                                    </div>
                                    
                                    <h4 class="font-bold text-lg text-[#1b3b2b] font-headline">Sugiere: {{ $ai['product']['name'] }}</h4>
                                    
                                    <p class="text-xs text-[#2a5a3f] mt-3 font-body leading-relaxed max-w-[200px]">
                                        {{ $ai['reason'] }}
                                    </p>
                                    
                                    <p class="text-xs text-[#1b3b2b] mt-4 mb-6">Probabilidad de compra: <span class="font-bold">Alta</span></p>
                                    
                                    <a href="{{ route('catalog') }}" class="mt-auto w-full py-3 bg-[#2f5a43] text-white font-bold text-sm rounded-xl hover:bg-[#1b3b2b] transition-colors shadow-md flex items-center justify-center gap-2">
                                        <span class="material-symbols-outlined text-sm">magic_button</span>
                                        Crear Oferta
                                    </a>
                                @else
                                    <p class="text-sm text-[#2a5a3f] mt-10 font-body">Visita nuestro catálogo para obtener recomendaciones personalizadas.</p>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            @endif

            <!-- Section 3: Orders List -->
            @if($activeSection === 'orders')
                <div class="space-y-8 animate-fade-in">
                    <div>
                        <h1 class="text-3xl font-headline font-bold text-on-surface">Historial de Compras</h1>
                        <p class="text-on-surface-variant font-body mt-1 text-sm">Consulta el estado del envío y descarga las facturas de tus decants.</p>
                    </div>

                    <div class="space-y-4">
                        @forelse($this->clientOrders as $order)
                            <div class="bg-surface-container-low border border-outline-variant/20 rounded-2xl p-6 hover:shadow-md transition-all">
                                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4 border-b border-outline-variant/15 pb-4">
                                    <div>
                                        <span class="text-[10px] text-on-surface-variant font-bold uppercase font-body">Pedido Realizado</span>
                                        <p class="text-xs font-bold text-on-surface mt-0.5">{{ $order['date'] }}</p>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-on-surface-variant font-bold uppercase font-body">Código de Pedido</span>
                                        <p class="text-xs font-bold text-on-surface mt-0.5">{{ $order['id'] }}</p>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-on-surface-variant font-bold uppercase font-body">Importe Total</span>
                                        <p class="text-xs font-bold text-primary mt-0.5">${{ number_format($order['total'], 0, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold {{ $order['status'] === 'Enviado' ? 'bg-secondary-container text-on-secondary-container' : ($order['status'] === 'Pagado' ? 'bg-primary-container text-on-primary-container' : 'bg-error-container text-on-error-container') }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $order['status'] === 'Enviado' ? 'bg-primary' : ($order['status'] === 'Pagado' ? 'bg-tertiary' : 'bg-error') }}"></span>
                                            {{ $order['status'] }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                    <div class="flex items-center gap-3">
                                        <span class="material-symbols-outlined text-primary text-[28px]">box_pack</span>
                                        <div class="text-xs font-body">
                                            <p class="font-bold text-on-surface">{{ $order['items'] }}</p>
                                            <p class="text-on-surface-variant text-[11px] mt-0.5">Presentación: Frasco Decant Atomizador de Vidrio premium</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2 w-full md:w-auto">
                                        <button class="flex-1 md:flex-initial px-4 py-2 border border-outline-variant text-[11px] font-bold text-on-surface-variant rounded-full hover:bg-surface-container-high transition-colors">Seguimiento</button>
                                        <button class="flex-1 md:flex-initial px-4 py-2 bg-primary text-on-primary text-[11px] font-bold rounded-full hover:shadow transition-all">Ver Detalle</button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="bg-surface-container-low border border-outline-variant/20 rounded-2xl p-8 text-center py-12">
                                <span class="material-symbols-outlined text-[48px] text-on-surface-variant mb-3">shopping_bag</span>
                                <h3 class="font-bold font-headline text-lg">Aún no tienes compras realizadas</h3>
                                <p class="text-xs text-on-surface-variant font-body max-w-sm mx-auto mt-2 leading-relaxed">Descubre nuestras esencias premium y consigue decants originales con envío rápido a todo el país.</p>
                                <a href="{{ route('catalog') }}" class="mt-6 inline-flex items-center gap-1 px-5 py-2.5 bg-primary text-on-primary rounded-full text-xs font-bold hover:shadow transition-all">Explorar Catálogo</a>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif

            <!-- Section 4: Profile Settings -->
            @if($activeSection === 'settings')
                <div class="space-y-8 animate-fade-in">
                    <div>
                        <h1 class="text-3xl font-headline font-bold text-on-surface">Ajustes de Perfil</h1>
                        <p class="text-on-surface-variant font-body mt-1 text-sm">Gestiona la información de seguridad de tu cuenta de cliente.</p>
                    </div>

                    <div class="bg-surface-container-low border border-outline-variant/20 rounded-2xl p-8 max-w-xl">
                        @if (session()->has('profile_success'))
                            <div class="p-4 bg-primary/10 text-primary rounded-xl border border-primary/20 flex items-start gap-3 shadow-sm mb-6 font-body animate-fade-in">
                                <span class="material-symbols-outlined mt-0.5" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                <div>
                                    <h4 class="font-bold text-sm">Operación Completada</h4>
                                    <p class="text-xs">{{ session('profile_success') }}</p>
                                </div>
                            </div>
                        @endif

                        <form wire:submit.prevent="updateProfile" class="space-y-5 font-body">
                            <div>
                                <label class="text-xs font-bold text-on-surface-variant mb-1.5 block">Nombre Completo</label>
                                <input type="text" wire:model="name" class="w-full px-4 py-2.5 border border-outline-variant/30 bg-surface rounded-xl text-sm focus:outline-none focus:border-primary">
                                @error('name') <span class="text-error text-xs block mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="text-xs font-bold text-on-surface-variant mb-1.5 block">Dirección de Email</label>
                                <input type="email" wire:model="email" class="w-full px-4 py-2.5 border border-outline-variant/30 bg-surface rounded-xl text-sm focus:outline-none focus:border-primary">
                                @error('email') <span class="text-error text-xs block mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="text-xs font-bold text-on-surface-variant mb-1.5 block">Ubicación (Ciudad, País)</label>
                                <input type="text" wire:model="location" class="w-full px-4 py-2.5 border border-outline-variant/30 bg-surface rounded-xl text-sm focus:outline-none focus:border-primary" placeholder="Ej. Buenos Aires, Argentina">
                                @error('location') <span class="text-error text-xs block mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="text-xs font-bold text-on-surface-variant mb-1.5 block">Teléfono Móvil</label>
                                <input type="text" wire:model="phone" class="w-full px-4 py-2.5 border border-outline-variant/30 bg-surface rounded-xl text-sm focus:outline-none focus:border-primary" placeholder="Ej. +54 9 11 1234 5678">
                                @error('phone') <span class="text-error text-xs block mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="text-xs font-bold text-on-surface-variant mb-1.5 block">Código Postal</label>
                                <input type="text" wire:model="postal_code" class="w-full px-4 py-2.5 border border-outline-variant/30 bg-surface rounded-xl text-sm focus:outline-none focus:border-primary" placeholder="Ej. 1414">
                                @error('postal_code') <span class="text-error text-xs block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="pt-4 border-t border-outline-variant/20">
                                <button type="submit" class="px-6 py-3 bg-primary text-on-primary rounded-full text-xs font-bold hover:shadow-md transition-all">Guardar Cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </main>
    </div>
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
