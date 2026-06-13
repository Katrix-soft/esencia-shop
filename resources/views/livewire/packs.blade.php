<div class="pt-24 pb-16 px-6 max-w-7xl mx-auto w-full">
    <!-- Hero Title -->
    <header class="mb-12 text-center md:text-left">
        <h1 class="text-4xl md:text-5xl font-headline font-bold text-primary mb-4">Packs &amp; Colecciones</h1>
        <p class="text-secondary max-w-2xl text-lg font-body">Descubre el arte de la perfumería a través de nuestras curadurías exclusivas. El regalo perfecto o la oportunidad ideal para encontrar tu esencia característica.</p>
    </header>

    <!-- Collections Bento Grid -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 mb-20">
        @if(isset($dbPacks) && count($dbPacks) > 0)
            @foreach($dbPacks as $index => $pack)
                @php
                    $style = $index % 3;
                    $imgSrc = $pack->image ? (str_contains($pack->image, 'http') ? $pack->image : asset('storage/'.$pack->image)) : 'https://placehold.co/600x400/e9e4d4/2f5a43?text='.urlencode($pack->name);
                @endphp

                @if($style === 0)
                    <!-- Style 0: Large Horizontal Card (col-span-8) -->
                    <div class="md:col-span-8 group relative overflow-hidden bg-surface-container-low rounded-xl card-shadow flex flex-col md:flex-row h-full border border-outline-variant/10">
                        <div class="md:w-1/2 overflow-hidden h-64 md:h-full">
                            <img alt="{{ $pack->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" src="{{ $imgSrc }}"/>
                        </div>
                        <div class="md:w-1/2 p-8 flex flex-col justify-center">
                            <div class="flex items-center gap-2 text-tertiary mb-3">
                                <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">eco</span>
                                <span class="text-xs font-bold uppercase tracking-widest">Lo más buscado</span>
                            </div>
                            <h2 class="text-3xl font-headline font-bold text-on-surface mb-4">{{ $pack->name }}</h2>
                            <p class="text-on-surface-variant font-body mb-6">{{ Str::limit($pack->description, 120) }}</p>
                            @if($pack->product_ids && count($pack->product_ids) > 0)
                            <ul class="space-y-2 mb-8 text-secondary font-body text-sm">
                                <li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-lg">check_circle</span> Contiene {{ count($pack->product_ids) }} fragancias seleccionadas</li>
                                <li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-lg">check_circle</span> Combinación exclusiva</li>
                            </ul>
                            @endif
                            <div class="mt-auto flex items-center justify-between">
                                <span class="text-2xl font-headline font-bold text-primary">{{ cache('store_currency', 'ARS') === 'EUR' ? '€' : '$' }}{{ number_format($pack->discounted_price, 2) }}</span>
                                <button wire:click="addToCart('pack_{{ $pack->id }}')" class="bg-primary text-on-primary px-6 py-3 rounded-lg font-bold flex items-center gap-2 active:scale-95 transition-all hover:bg-opacity-90">
                                    <span class="material-symbols-outlined text-sm">shopping_bag</span>
                                    Añadir al Carrito
                                </button>
                            </div>
                        </div>
                    </div>
                @elseif($style === 1)
                    <!-- Style 1: Square Card (col-span-4) -->
                    <div class="md:col-span-4 group relative overflow-hidden bg-surface-container-high rounded-xl card-shadow flex flex-col border border-outline-variant/10">
                        <div class="h-64 overflow-hidden">
                            <img alt="{{ $pack->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" src="{{ $imgSrc }}"/>
                        </div>
                        <div class="p-6 flex flex-col flex-grow">
                            <h2 class="text-2xl font-headline font-bold text-on-surface mb-2">{{ $pack->name }}</h2>
                            <p class="text-on-surface-variant font-body text-sm mb-6">{{ Str::limit($pack->description, 90) }}</p>
                            <div class="mt-auto">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-xl font-headline font-bold text-primary">{{ cache('store_currency', 'ARS') === 'EUR' ? '€' : '$' }}{{ number_format($pack->discounted_price, 2) }}</span>
                                    @if($pack->discount > 0)
                                        <span class="text-xs bg-error/10 text-error px-2 py-1 rounded font-bold">-{{ $pack->discount }}%</span>
                                    @else
                                        <span class="text-xs bg-tertiary-container/30 text-on-tertiary-container px-2 py-1 rounded">Edición Limitada</span>
                                    @endif
                                </div>
                                <button wire:click="addToCart('pack_{{ $pack->id }}')" class="w-full bg-secondary text-on-secondary px-6 py-3 rounded-lg font-bold flex items-center justify-center gap-2 active:scale-95 transition-all hover:bg-on-surface">
                                    <span class="material-symbols-outlined text-sm">card_giftcard</span>
                                    Añadir al Carrito
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Style 2: Wide Card (col-span-12) -->
                    <div class="md:col-span-12 group relative overflow-hidden bg-surface-container-lowest rounded-xl card-shadow flex flex-col md:flex-row border border-outline-variant/10 h-auto md:h-80">
                        <div class="md:w-2/5 p-8 flex flex-col justify-center order-2 md:order-1">
                            <div class="flex items-center gap-2 text-primary mb-3">
                                <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="text-xs font-bold uppercase tracking-widest">Colección Premium</span>
                            </div>
                            <h2 class="text-3xl font-headline font-bold text-on-surface mb-4">{{ $pack->name }}</h2>
                            <p class="text-on-surface-variant font-body mb-6">{{ Str::limit($pack->description, 150) }}</p>
                            <div class="flex items-center gap-4">
                                <span class="text-2xl font-headline font-bold text-primary">{{ cache('store_currency', 'ARS') === 'EUR' ? '€' : '$' }}{{ number_format($pack->discounted_price, 2) }}</span>
                                <button wire:click="addToCart('pack_{{ $pack->id }}')" class="bg-primary text-on-primary px-8 py-3 rounded-lg font-bold flex items-center gap-2 active:scale-95 transition-all hover:bg-opacity-90">
                                    <span class="material-symbols-outlined text-sm">auto_awesome</span>
                                    Lo Quiero
                                </button>
                            </div>
                        </div>
                        <div class="md:w-3/5 overflow-hidden order-1 md:order-2">
                            <img alt="{{ $pack->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" src="{{ $imgSrc }}"/>
                        </div>
                    </div>
                @endif
            @endforeach
        @else
            <div class="col-span-1 md:col-span-12 py-16 text-center bg-surface-container-low rounded-xl border border-outline-variant/10">
                <span class="material-symbols-outlined text-outline-variant text-5xl mb-4">inventory_2</span>
                <h3 class="text-2xl font-headline font-bold text-on-surface">Aún no hay colecciones</h3>
                <p class="text-on-surface-variant font-body mt-2">Los nuevos packs creados desde el administrador aparecerán aquí con diseños dinámicos.</p>
            </div>
        @endif
    </div>

    <!-- Custom Set Section -->
    <section class="mt-20 relative overflow-hidden rounded-2xl bg-secondary-container p-8 md:p-16 border border-tertiary-container/20">
        <div class="relative z-10 grid md:grid-cols-2 gap-12 items-center">
            <div>
                <span class="text-tertiary font-bold tracking-[0.2em] uppercase text-xs mb-4 block">Artesanía Olfativa</span>
                <h2 class="text-4xl md:text-5xl font-headline font-bold text-on-secondary-container mb-6">Crea tu propio set personalizado</h2>
                <p class="text-on-secondary-container/80 text-lg font-body mb-8">Elige 5 fragancias que definan tu estilo y nosotros las prepararemos exclusivamente para vos.</p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('catalog') }}" wire:navigate class="bg-primary text-on-primary px-8 py-4 rounded-full font-bold shadow-lg hover:shadow-xl transition-all active:scale-95 inline-block text-center">Comenzar a Crear</a>
                    <a href="{{ route('perfil-olfativo') }}" wire:navigate class="bg-transparent border-2 border-primary text-primary px-8 py-4 rounded-full font-bold hover:bg-primary/5 transition-all inline-block text-center">Ver Guía Olfativa</a>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-4">
                    <div class="bg-surface-bright p-4 rounded-xl card-shadow aspect-square flex flex-col items-center justify-center text-center">
                        <span class="material-symbols-outlined text-tertiary text-3xl mb-2">vape_free</span>
                        <p class="font-headline font-bold text-sm">Viales</p>
                    </div>
                    <div class="bg-surface-bright p-4 rounded-xl card-shadow aspect-square flex flex-col items-center justify-center text-center -translate-x-6">
                        <span class="material-symbols-outlined text-tertiary text-3xl mb-2">inventory_2</span>
                        <p class="font-headline font-bold text-sm">Packaging</p>
                    </div>
                </div>
                <div class="space-y-4 pt-8">
                    <div class="bg-surface-bright p-4 rounded-xl card-shadow aspect-square flex flex-col items-center justify-center text-center translate-x-6">
                        <span class="material-symbols-outlined text-tertiary text-3xl mb-2">palette</span>
                        <p class="font-headline font-bold text-sm">Personalización</p>
                    </div>
                    <div class="bg-surface-bright p-4 rounded-xl card-shadow aspect-square flex flex-col items-center justify-center text-center">
                        <span class="material-symbols-outlined text-tertiary text-3xl mb-2">local_shipping</span>
                        <p class="font-headline font-bold text-sm">Envío</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Decorative Elements -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-tertiary/5 rounded-full -ml-24 -mb-24 blur-2xl"></div>
    </section>
</div>
