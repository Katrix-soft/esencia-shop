<div class="w-full relative">
    <!-- Profile Success Alert -->
    @if (session()->has('profile_success'))
        <div class="max-w-7xl mx-auto px-6 pt-28 -mb-12">
            <div class="p-4 bg-on-primary-container text-on-primary-fixed-variant rounded-xl border border-primary/20 flex items-start gap-3 shadow-md animate-fade-in">
                <span class="material-symbols-outlined text-on-primary-fixed-variant mt-0.5" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                <div>
                    <h4 class="font-bold text-on-primary-fixed-variant text-sm mb-1 font-body">¡Perfil Actualizado!</h4>
                    <p class="text-xs leading-relaxed font-body">{{ session('profile_success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Hero Header -->
    <header class="relative h-[60vh] min-h-[450px] flex items-center justify-center overflow-hidden pt-20">
        <div class="absolute inset-0 z-0">
            <img class="w-full h-full object-cover opacity-90 scale-105" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB_Zd9QZqnjJubHQoDUWhPLIeCbPS_PnjXyRCrb1XcOTRZXPWOqAg13HMDUib9o8SXFWGkYVmj0eDoD3bYupXzDbfV8AO_uxv4aDGWF3X3qJx8VP5Nm-tmOskUFuo2pWP21I_4R5XKtUwOAPcq5gPlGTJqo4PLNvSFMlOheacxQXWbVamQ0fFSJ0-f6_SI7tCx0YpnVrejYM0EleCPzmL3qlbXjyvzVImva5UknuwGxswckMBWVja7M2uNPtrs2nnsbMo_NcIrA-CY"/>
            <div class="absolute inset-0 bg-gradient-to-b from-background/10 via-background/20 to-background"></div>
        </div>
        <div class="relative z-10 text-center px-6 max-w-3xl">
            <span class="inline-block py-1 px-4 mb-6 bg-tertiary-container/30 text-tertiary font-bold rounded-full text-xs tracking-widest uppercase font-body">Inteligencia Olfativa</span>
            <h1 class="text-5xl md:text-7xl font-display font-bold text-on-surface tracking-tight mb-6">Tu ADN Olfativo</h1>
            <p class="text-xl md:text-2xl text-on-surface-variant font-body leading-relaxed max-w-2xl mx-auto">Descubre la ciencia detrás de tus gustos y explora el universo sensorial diseñado exclusivamente para ti.</p>
        </div>
    </header>

    <!-- Profile Dashboard -->
    <section class="max-w-7xl mx-auto px-6 py-20">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Scent DNA Visualization (Bento Style) -->
            <div class="lg:col-span-7 bg-surface-container-low rounded-xl p-8 shadow-[0_4px_20px_rgba(46,50,48,0.06)] border border-outline-variant/20 flex flex-col items-center justify-center text-center">
                <div class="mb-8 w-full flex justify-between items-start">
                    <div class="text-left">
                        <h2 class="text-2xl font-bold mb-2 font-headline">Composición Maestra</h2>
                        <p class="text-on-surface-variant font-body">Análisis detallado de tus preferencias.</p>
                    </div>
                    <span class="material-symbols-outlined text-primary text-4xl" style="font-variation-settings: 'FILL' 1;">auto_awesome</span>
                </div>
                <div class="relative w-72 h-72 md:w-96 md:h-96 flex items-center justify-center">
                    <svg class="w-full h-full -rotate-90" viewBox="0 0 100 100">
                        <!-- Background Circles -->
                        <circle class="text-outline-variant/30" cx="50" cy="50" fill="none" r="40" stroke="currentColor" stroke-width="0.5"></circle>
                        <circle class="text-outline-variant/30" cx="50" cy="50" fill="none" r="30" stroke="currentColor" stroke-width="0.5"></circle>
                        <circle class="text-outline-variant/30" cx="50" cy="50" fill="none" r="20" stroke="currentColor" stroke-width="0.5"></circle>
                        
                        <!-- Amaderados -->
                        <circle class="transition-all duration-1000 ease-out" 
                                cx="50" cy="50" fill="none" r="40" stroke="#4a7c59" stroke-dasharray="251.3" 
                                stroke-dashoffset="{{ 251.3 - (251.3 * $woodPercent / 100) }}" stroke-width="8"></circle>
                        
                        <!-- Cítricos -->
                        <circle class="transition-all duration-1000 ease-out" 
                                cx="50" cy="50" fill="none" r="30" stroke="#705c30" stroke-dasharray="188.5" 
                                stroke-dashoffset="{{ 188.5 - (188.5 * $citrusPercent / 100) }}" stroke-width="8"></circle>
                        
                        <!-- Florales -->
                        <circle class="transition-all duration-1000 ease-out" 
                                cx="50" cy="50" fill="none" r="20" stroke="#dcc48e" stroke-dasharray="125.7" 
                                stroke-dashoffset="{{ 125.7 - (125.7 * $floralPercent / 100) }}" stroke-width="8"></circle>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-4xl font-display font-bold text-primary animate-pulse">{{ $affinityScore }}%</span>
                        <span class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest font-body">Afinidad IA</span>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4 mt-12 w-full">
                    <div class="flex flex-col items-center">
                        <div class="w-3 h-3 rounded-full bg-primary mb-2"></div>
                        <span class="text-xs font-bold text-on-surface-variant font-body">Amaderados</span>
                        <span class="text-lg font-display text-primary">{{ $woodPercent }}%</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-3 h-3 rounded-full bg-tertiary mb-2"></div>
                        <span class="text-xs font-bold text-on-surface-variant font-body">Cítricos</span>
                        <span class="text-lg font-display text-tertiary">{{ $citrusPercent }}%</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-3 h-3 rounded-full bg-tertiary-fixed-dim mb-2"></div>
                        <span class="text-xs font-bold text-on-surface-variant font-body">Florales/Otros</span>
                        <span class="text-lg font-display text-tertiary-fixed-dim">{{ $floralPercent }}%</span>
                    </div>
                </div>
            </div>

            <!-- Preferred Notes (Bento Style) -->
            <div class="lg:col-span-5 space-y-8">
                <div class="bg-surface-container-high rounded-xl p-8 shadow-[0_4px_20px_rgba(46,50,48,0.06)] border border-outline-variant/20">
                    <h2 class="text-2xl font-bold mb-6 flex items-center gap-3 font-headline">
                        Notas Predilectas
                        <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">nest_eco_leaf</span>
                    </h2>
                    <div class="flex flex-wrap gap-3">
                        @foreach($preferredNotes as $note)
                            <div class="group px-5 py-3.5 bg-background rounded-xl border border-outline-variant/30 hover:border-primary/50 transition-all cursor-pointer flex items-center gap-2.5 active:scale-95">
                                <span class="material-symbols-outlined text-primary opacity-60 group-hover:opacity-100 text-[20px]">
                                    @if(in_array($note, ['Sándalo', 'Cedro', 'Vetiver', 'Pachulí']))
                                        forest
                                    @elseif(in_array($note, ['Mandarina', 'Bergamota', 'Limón', 'Neroli']))
                                        eco
                                    @elseif(in_array($note, ['Jazmín', 'Rosa', 'Lavanda', 'Vainilla']))
                                        psychiatry
                                    @else
                                        local_fire_department
                                    @endif
                                </span>
                                <span class="font-bold text-sm font-body">{{ $note }}</span>
                            </div>
                        @endforeach
                    </div>
                    <a href="{{ route('catalog') }}" wire:navigate class="mt-8 text-primary font-bold inline-flex items-center gap-2 hover:underline text-sm font-body">
                        Explorar catálogo por notas
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>

                <!-- Recent Activity -->
                <div class="bg-surface-container-lowest rounded-xl p-8 border border-outline-variant/20 shadow-[0_4px_20px_rgba(46,50,48,0.06)]">
                    <h2 class="text-xl font-bold mb-6 font-headline">Actividad Reciente</h2>
                    <div class="space-y-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-surface-container-low rounded-lg flex items-center justify-center border border-outline-variant/20">
                                <span class="material-symbols-outlined text-on-surface-variant">search</span>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-bold font-body">Exploraste: Oud Wood</p>
                                <p class="text-xs text-on-surface-variant font-body">Hace 2 horas</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center border border-primary/10">
                                <span class="material-symbols-outlined text-primary">shopping_cart</span>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-bold font-body">Compraste: Terre d'Hermès</p>
                                <p class="text-xs text-on-surface-variant font-body">Ayer, 14:20</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Smart Recommendations (IA) -->
    <section class="bg-surface-container-highest/30 py-24 border-y border-outline-variant/10">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
                <div>
                    <span class="text-primary font-bold tracking-widest uppercase text-xs font-body">Curaduría Inteligente</span>
                    <h2 class="text-4xl font-display font-bold mt-2">Recomendaciones para ti</h2>
                </div>
                <a href="{{ route('catalog') }}" wire:navigate class="bg-primary text-on-primary px-8 py-3 rounded-full font-bold hover:shadow-lg transition-all active:scale-95 font-body">
                    Ver selección completa
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($recommendations as $prod)
                    <div class="group bg-background rounded-xl overflow-hidden hover:shadow-xl border border-outline-variant/10 transition-all duration-500 transform hover:-translate-y-2">
                        <div class="aspect-[4/5] bg-surface-container overflow-hidden relative">
                            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" src="{{ $prod['img'] }}"/>
                            @if($prod['tag'])
                                <div class="absolute top-4 right-4 bg-white/95 backdrop-blur-sm px-3 py-1 rounded-full text-[10px] font-bold tracking-widest text-primary uppercase font-body shadow-sm border border-outline-variant/10">{{ $prod['tag'] }}</div>
                            @endif
                        </div>
                        <div class="p-8">
                            <p class="text-on-surface-variant text-xs font-bold tracking-widest uppercase mb-2 font-body">{{ $prod['type'] }}</p>
                            <h3 class="text-2xl font-bold mb-4 font-headline text-on-surface">{{ $prod['name'] }}</h3>
                            <div class="flex justify-between items-center">
                                <span class="text-xl font-display text-primary font-body">${{ number_format($prod['price'], 2) }}</span>
                                <button class="w-10 h-10 rounded-full border border-primary text-primary flex items-center justify-center hover:bg-primary hover:text-on-primary transition-colors">
                                    <span class="material-symbols-outlined text-sm">add_shopping_cart</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="max-w-7xl mx-auto px-6 py-24 animate-fade-in">
        <div class="bg-primary rounded-xl p-12 text-on-primary relative overflow-hidden flex flex-col items-center text-center">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-black/10 rounded-full blur-3xl animate-pulse"></div>
            <h2 class="text-4xl font-display font-bold mb-6 max-w-2xl relative z-10">¿Quieres profundizar en tu perfil?</h2>
            <p class="text-lg text-primary-container font-body mb-10 max-w-xl relative z-10">Realiza nuestro test avanzado de 3 minutos y desbloquea el 100% de la precisión de nuestra IA olfativa.</p>
            <button wire:click="startTest" class="bg-surface text-primary px-10 py-4 rounded-full font-bold shadow-xl hover:bg-surface-bright transition-all active:scale-95 relative z-10 font-body">
                Comenzar Test Avanzado
            </button>
        </div>
    </section>

    <!-- Advanced Test Wizard Modal Overlay -->
    @if($inTest)
        <div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 animate-fade-in">
            <div class="bg-surface rounded-2xl p-8 max-w-2xl w-full border border-outline-variant/30 shadow-2xl relative overflow-hidden transition-all duration-300">
                
                <!-- Close Button -->
                <button wire:click="$set('inTest', false)" class="absolute top-4 right-4 w-10 h-10 rounded-full hover:bg-secondary-container flex items-center justify-center text-on-surface-variant transition-colors z-10">
                    <span class="material-symbols-outlined">close</span>
                </button>

                <!-- Progress Bar -->
                <div class="w-full bg-surface-container-low h-1.5 rounded-full mb-8 relative overflow-hidden">
                    <div class="bg-primary h-full transition-all duration-500" style="width: {{ ($currentStep / $totalSteps) * 100 }}%"></div>
                </div>

                @if(!$isAnalyzing)
                    <!-- Steps Content -->
                    <div class="mb-6">
                        <span class="text-xs font-bold text-primary tracking-widest uppercase mb-1 block font-body">Paso {{ $currentStep }} de {{ $totalSteps }}</span>
                        
                        @if($currentStep == 1)
                            <!-- Paso 1 -->
                            <h3 class="text-2xl font-headline font-bold text-on-surface mb-6">¿Qué acordes de aromas resuenan más contigo?</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <button wire:click="$set('selectedAroma', 'amaderado')" class="p-5 rounded-xl border-2 text-left transition-all duration-200 flex items-center gap-4 font-body {{ $selectedAroma === 'amaderado' ? 'border-primary bg-primary/5 text-primary' : 'border-outline-variant hover:border-primary/50 text-on-surface' }}">
                                    <span class="material-symbols-outlined text-3xl">forest</span>
                                    <div>
                                        <h4 class="font-bold">Amaderados y Boscosos</h4>
                                        <p class="text-xs text-on-surface-variant">Sándalo, cedro, vetiver y musgo.</p>
                                    </div>
                                </button>
                                <button wire:click="$set('selectedAroma', 'citrico')" class="p-5 rounded-xl border-2 text-left transition-all duration-200 flex items-center gap-4 font-body {{ $selectedAroma === 'citrico' ? 'border-primary bg-primary/5 text-primary' : 'border-outline-variant hover:border-primary/50 text-on-surface' }}">
                                    <span class="material-symbols-outlined text-3xl">eco</span>
                                    <div>
                                        <h4 class="font-bold">Cítricos y Frescos</h4>
                                        <p class="text-xs text-on-surface-variant">Mandarina, bergamota, limón y neroli.</p>
                                    </div>
                                </button>
                                <button wire:click="$set('selectedAroma', 'floral')" class="p-5 rounded-xl border-2 text-left transition-all duration-200 flex items-center gap-4 font-body {{ $selectedAroma === 'floral' ? 'border-primary bg-primary/5 text-primary' : 'border-outline-variant hover:border-primary/50 text-on-surface' }}">
                                    <span class="material-symbols-outlined text-3xl">psychiatry</span>
                                    <div>
                                        <h4 class="font-bold">Florales y Herbáceos</h4>
                                        <p class="text-xs text-on-surface-variant">Jazmín, rosa, lavanda y violeta.</p>
                                    </div>
                                </button>
                                <button wire:click="$set('selectedAroma', 'oriental')" class="p-5 rounded-xl border-2 text-left transition-all duration-200 flex items-center gap-4 font-body {{ $selectedAroma === 'oriental' ? 'border-primary bg-primary/5 text-primary' : 'border-outline-variant hover:border-primary/50 text-on-surface' }}">
                                    <span class="material-symbols-outlined text-3xl">local_fire_department</span>
                                    <div>
                                        <h4 class="font-bold">Orientales y Especiados</h4>
                                        <p class="text-xs text-on-surface-variant">Ámbar, cardamomo, vainilla y tabaco.</p>
                                    </div>
                                </button>
                            </div>
                        @elseif($currentStep == 2)
                            <!-- Paso 2 -->
                            <h3 class="text-2xl font-headline font-bold text-on-surface mb-6">¿Para qué ocasiones buscas tu fragancia ideal?</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <button wire:click="$set('selectedOccasion', 'diario')" class="p-5 rounded-xl border-2 text-left transition-all duration-200 flex items-center gap-4 font-body {{ $selectedOccasion === 'diario' ? 'border-primary bg-primary/5 text-primary' : 'border-outline-variant hover:border-primary/50 text-on-surface' }}">
                                    <span class="material-symbols-outlined text-3xl">work</span>
                                    <div>
                                        <h4 class="font-bold">Uso Diario y Oficina</h4>
                                        <p class="text-xs text-on-surface-variant">Aromas sutiles, limpios y versátiles.</p>
                                    </div>
                                </button>
                                <button wire:click="$set('selectedOccasion', 'noche')" class="p-5 rounded-xl border-2 text-left transition-all duration-200 flex items-center gap-4 font-body {{ $selectedOccasion === 'noche' ? 'border-primary bg-primary/5 text-primary' : 'border-outline-variant hover:border-primary/50 text-on-surface' }}">
                                    <span class="material-symbols-outlined text-3xl">dark_mode</span>
                                    <div>
                                        <h4 class="font-bold">Noche y Eventos</h4>
                                        <p class="text-xs text-on-surface-variant">Aromas intensos, complejos y elegantes.</p>
                                    </div>
                                </button>
                                <button wire:click="$set('selectedOccasion', 'deporte')" class="p-5 rounded-xl border-2 text-left transition-all duration-200 flex items-center gap-4 font-body {{ $selectedOccasion === 'deporte' ? 'border-primary bg-primary/5 text-primary' : 'border-outline-variant hover:border-primary/50 text-on-surface' }}">
                                    <span class="material-symbols-outlined text-3xl">directions_run</span>
                                    <div>
                                        <h4 class="font-bold">Deporte y Actividad</h4>
                                        <p class="text-xs text-on-surface-variant">Aromas frescos, acuáticos y energizantes.</p>
                                    </div>
                                </button>
                                <button wire:click="$set('selectedOccasion', 'citas')" class="p-5 rounded-xl border-2 text-left transition-all duration-200 flex items-center gap-4 font-body {{ $selectedOccasion === 'citas' ? 'border-primary bg-primary/5 text-primary' : 'border-outline-variant hover:border-primary/50 text-on-surface' }}">
                                    <span class="material-symbols-outlined text-3xl">favorite</span>
                                    <div>
                                        <h4 class="font-bold">Citas y Romance</h4>
                                        <p class="text-xs text-on-surface-variant">Aromas íntimos, cálidos y seductores.</p>
                                    </div>
                                </button>
                            </div>
                        @elseif($currentStep == 3)
                            <!-- Paso 3 -->
                            <h3 class="text-2xl font-headline font-bold text-on-surface mb-6">¿Qué presencia deseas proyectar?</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <button wire:click="$set('selectedIntensity', 'sutil')" class="p-5 rounded-xl border-2 text-left transition-all duration-200 flex items-center gap-4 font-body {{ $selectedIntensity === 'sutil' ? 'border-primary bg-primary/5 text-primary' : 'border-outline-variant hover:border-primary/50 text-on-surface' }}">
                                    <span class="material-symbols-outlined text-3xl">visibility_off</span>
                                    <div>
                                        <h4 class="font-bold">Sutil y Cercana</h4>
                                        <p class="text-xs text-on-surface-variant">Perfumes íntimos y limpios de burbuja personal.</p>
                                    </div>
                                </button>
                                <button wire:click="$set('selectedIntensity', 'audaz')" class="p-5 rounded-xl border-2 text-left transition-all duration-200 flex items-center gap-4 font-body {{ $selectedIntensity === 'audaz' ? 'border-primary bg-primary/5 text-primary' : 'border-outline-variant hover:border-primary/50 text-on-surface' }}">
                                    <span class="material-symbols-outlined text-3xl">bolt</span>
                                    <div>
                                        <h4 class="font-bold">Audaz y Duradera</h4>
                                        <p class="text-xs text-on-surface-variant">Estela notable y magnética que marca presencia.</p>
                                    </div>
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between items-center mt-8 pt-4 border-t border-outline-variant/20">
                        <button wire:click="prevStep" class="px-6 py-3 rounded-full hover:bg-secondary-container transition-all font-bold text-sm text-primary flex items-center gap-2 {{ $currentStep == 1 ? 'opacity-0 pointer-events-none' : '' }} font-body">
                            <span class="material-symbols-outlined text-sm">arrow_back</span>
                            Anterior
                        </button>
                        
                        @php
                            $canNext = ($currentStep == 1 && $selectedAroma) || ($currentStep == 2 && $selectedOccasion) || ($currentStep == 3 && $selectedIntensity);
                        @endphp
                        <button wire:click="nextStep" @if(!$canNext) disabled @endif class="bg-primary text-on-primary px-8 py-3 rounded-full font-bold text-sm shadow-md hover:bg-on-primary-fixed-variant transition-all active:scale-95 flex items-center gap-2 disabled:opacity-50 font-body">
                            {{ $currentStep == $totalSteps ? 'Calcular ADN' : 'Siguiente' }}
                            <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </button>
                    </div>
                @else
                    <!-- Loading / Analyzing screen -->
                    <div class="flex flex-col items-center justify-center py-12 text-center"
                         x-init="setTimeout(() => { $wire.completeTest() }, 3000)">
                        <div class="relative w-24 h-24 mb-8">
                            <!-- Loader rings -->
                            <div class="absolute inset-0 border-4 border-primary/20 rounded-full"></div>
                            <div class="absolute inset-0 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
                            <span class="material-symbols-outlined text-primary text-4xl absolute inset-0 flex items-center justify-center animate-pulse" style="font-variation-settings: 'FILL' 1;">spa</span>
                        </div>
                        <h3 class="text-2xl font-headline font-bold mb-3 text-on-surface">Analizando tu Perfil</h3>
                        <p class="text-on-surface-variant max-w-sm leading-relaxed font-body" x-data="{ stepText: 'Destilando notas aromáticas...' }" x-init="
                            setTimeout(() => stepText = 'Comparando acordes con nuestra IA...', 1000);
                            setTimeout(() => stepText = 'Calculando afinidad molecular...', 2000);
                        " x-text="stepText"></p>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.4s ease-out forwards;
    }
</style>
