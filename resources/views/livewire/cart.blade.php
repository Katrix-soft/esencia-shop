<div class="container mx-auto px-4 md:px-8 py-12 max-w-7xl pt-24 flex-grow">
    <header class="mb-10">
        <h1 class="text-4xl font-headline font-bold text-primary mb-2">Tu Cesta Aromática</h1>
        <p class="text-on-surface-variant text-lg">Revisa los decants que has seleccionado antes de finalizar.</p>
    </header>

    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-error-container text-on-error-container rounded-xl border border-error/20 flex items-center gap-3">
            <span class="material-symbols-outlined">error</span>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    @if (count($items) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12">
            <!-- Cart Items List (Left Column) -->
            <div class="lg:col-span-8 flex flex-col gap-6">
                @foreach ($items as $item)
                    <div class="bg-surface rounded-xl p-6 flex flex-col sm:flex-row items-center gap-6 shadow-[0_4px_20px_rgba(46,50,48,0.06)] border border-outline-variant/20 group hover:border-primary/30 transition-colors">
                        <div class="w-32 h-32 flex-shrink-0 rounded-lg overflow-hidden bg-surface-container-low">
                            <img alt="{{ $item['name'] }} Decant" class="w-full h-full object-cover mix-blend-multiply opacity-90 group-hover:scale-105 transition-transform duration-500" src="{{ $item['img'] }}"/>
                        </div>
                        <div class="flex-grow flex flex-col w-full">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="text-xl font-headline font-bold text-on-surface">{{ $item['name'] }}</h3>
                                    <p class="text-sm text-secondary">{{ $item['type'] }}</p>
                                </div>
                                <button wire:click="removeItem({{ $item['id'] }})" aria-label="Eliminar" class="text-outline hover:text-error transition-colors">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </div>
                            <div class="text-sm text-on-surface-variant mb-4 bg-surface-container py-1 px-3 rounded-full self-start">
                                {{ $item['size'] }}
                            </div>
                            <div class="flex justify-between items-center mt-auto w-full">
                                <div class="flex items-center gap-3 bg-surface-container-low rounded-lg p-1 border border-outline-variant/30">
                                    <button wire:click="decreaseQuantity({{ $item['id'] }})" class="w-8 h-8 flex items-center justify-center text-on-surface-variant hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined text-sm">remove</span>
                                    </button>
                                    <span class="font-bold text-on-surface w-4 text-center">{{ $item['quantity'] }}</span>
                                    <button wire:click="increaseQuantity({{ $item['id'] }})" class="w-8 h-8 flex items-center justify-center text-on-surface-variant hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined text-sm">add</span>
                                    </button>
                                </div>
                                <div class="text-xl font-bold text-primary">${{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Sidebar (Right Column) -->
            <div class="lg:col-span-4 flex flex-col gap-8">
                <!-- Order Summary Card -->
                <div class="bg-surface-container-low rounded-xl p-8 shadow-[0_4px_20px_rgba(46,50,48,0.06)] flex flex-col gap-6 sticky top-28">
                    <h2 class="text-2xl font-headline font-bold text-on-surface border-b border-outline-variant/30 pb-4">Resumen</h2>
                    <div class="flex flex-col gap-3 text-on-surface-variant">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span class="font-bold text-on-surface">${{ number_format($this->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Envío ecológico</span>
                            <span class="font-bold text-on-surface">${{ number_format($shippingCost, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <!-- Points Badge -->
                    <div class="bg-tertiary-fixed/40 rounded-lg p-4 flex items-center gap-3 border border-tertiary/20">
                        <div class="bg-tertiary text-on-tertiary rounded-full p-2 flex items-center justify-center">
                            <span class="material-symbols-outlined text-sm">spa</span>
                        </div>
                        <div>
                            <p class="text-sm text-on-surface-variant">Puntos a ganar</p>
                            <p class="font-bold text-tertiary">+{{ $this->points }} Semillas</p>
                        </div>
                    </div>
                    <div class="flex justify-between items-center border-t border-outline-variant/30 pt-4 mt-2">
                        <span class="text-lg font-bold text-on-surface">Total</span>
                        <span class="text-3xl font-headline font-bold text-primary">${{ number_format($this->total, 0, ',', '.') }}</span>
                    </div>
                    <button wire:click="checkout" wire:loading.attr="disabled" class="w-full bg-primary text-on-primary rounded-xl py-4 font-bold text-lg hover:bg-on-primary-fixed-variant transition-colors active:scale-95 duration-150 flex justify-center items-center gap-2 shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove>Finalizar Compra</span>
                        <span wire:loading>Procesando...</span>
                        <span class="material-symbols-outlined" wire:loading.remove>arrow_forward</span>
                    </button>
                    <p class="text-xs text-center text-secondary mt-2 flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">lock</span> Pago 100% seguro y encriptado
                    </p>
                </div>

                <!-- Cross-selling Inteligente -->
                @if (!collect($items)->contains('id', 3))
                    <div class="bg-surface rounded-xl p-6 shadow-[0_4px_20px_rgba(46,50,48,0.06)] border border-tertiary/30 relative overflow-hidden group">
                        <div class="absolute top-0 right-0 bg-tertiary text-on-tertiary text-xs px-3 py-1 rounded-bl-lg font-bold flex items-center gap-1 z-10 shadow-sm">
                            <span class="material-symbols-outlined text-[14px]">auto_awesome</span> Sugerencia
                        </div>
                        <h3 class="text-lg font-headline font-bold text-on-surface mb-1 mt-2">Discovery Set</h3>
                        <p class="text-sm text-secondary mb-4">Pack Temático "Bosque Profundo"</p>
                        <div class="aspect-[4/3] rounded-lg overflow-hidden bg-surface-container mb-4 relative">
                            <img alt="Discovery Set Box" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity duration-300" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDjaiTcbzPSfNSen_8KeCj9tg6snb-wsNFNsVOjUyihLc6tFrJr1zhdwjDNlZaNwS-o00FVl0ISAYTjni3cXfA2LADJrvJyJIP6ZQqrqtQ_vr5wiFqO792DJqbUWSfyzUE6S-wV6hT-KB1UUFu1IrD3vJ3rVQH2qxdfxhTFjq7orQaD-Uit0rCrpS-cLWt66ckUvsKy-Khnm_e_Npg8A3pyf6TkaUqGdsFQgeTWPSn3zVzdNw5eRQKhq_hLAkhW7oe-1xU7ZOGVqrA"/>
                            <div class="absolute inset-0 bg-gradient-to-t from-surface/80 to-transparent flex items-end p-3">
                                <span class="text-sm font-bold text-primary bg-surface/90 px-2 py-1 rounded backdrop-blur-sm">$32.000</span>
                            </div>
                        </div>
                        <p class="text-sm text-on-surface-variant mb-5 line-clamp-2">Explora 3 fragancias amaderadas adicionales que complementan perfectamente el Santal Raíz que ya elegiste.</p>
                        <button wire:click="addDiscoverySet" class="w-full bg-surface-container-highest text-primary font-bold py-2.5 rounded-lg border border-primary/20 hover:bg-primary hover:text-on-primary transition-colors flex justify-center items-center gap-2">
                            <span class="material-symbols-outlined text-sm">add_shopping_cart</span>
                            Añadir a la cesta
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-surface rounded-xl p-12 text-center shadow-[0_4px_20px_rgba(46,50,48,0.06)] border border-outline-variant/20 flex flex-col items-center justify-center max-w-xl mx-auto mt-12">
            <span class="material-symbols-outlined text-outline text-6xl mb-6">shopping_bag</span>
            <h2 class="text-2xl font-headline font-bold text-on-surface mb-3">Tu cesta está vacía</h2>
            <p class="text-on-surface-variant mb-8 font-body">Explora nuestro catálogo y añade algunas esencias personalizadas para comenzar tu viaje olfativo.</p>
            <a href="{{ route('catalog') }}" wire:navigate class="bg-primary text-on-primary px-8 py-3 rounded-lg font-bold flex items-center gap-2 active:scale-95 transition-all hover:bg-opacity-90 shadow-md">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                Ver Catálogo
            </a>
        </div>
    @endif
</div>
