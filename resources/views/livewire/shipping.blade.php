@assets
    <script src="https://sdk.mercadopago.com/js/v2"></script>
@endassets

<div class="container mx-auto px-4 md:px-8 py-12 max-w-7xl pt-24 flex-grow"
     x-data="{
         showModal: false,
         paymentStatus: null,
         ticketUrl: null,
         loading: false,
         async initPaymentBrick(preferenceId, totalAmount) {
             if (typeof MercadoPago === 'undefined') {
                 console.error('MercadoPago SDK no está cargado.');
                 return;
             }
             
             const container = document.getElementById('paymentBrick_container');
             if (container) {
                 container.innerHTML = '';
             }
             
             const mp = new MercadoPago('{{ config('services.mercadopago.public_key') }}', {
                 locale: 'es-AR'
             });
             const bricksBuilder = mp.bricks();
             
             window.paymentBrickController = await bricksBuilder.create(
                 'payment',
                 'paymentBrick_container',
                 {
                     initialization: {
                         amount: parseFloat(totalAmount),
                         preferenceId: preferenceId
                     },
                     customization: {
                         paymentMethods: {
                             ticket: 'all',
                             creditCard: 'all',
                             debitCard: 'all',
                             mercadoPago: 'all'
                         }
                     },
                     callbacks: {
                         onReady: () => {
                             // Ready
                         },
                         onSubmit: ({ selectedPaymentMethod, formData }) => {
                             return new Promise((resolve, reject) => {
                                 $wire.processPayment(formData)
                                     .then(result => {
                                         if (result && result.success) {
                                             resolve();
                                             this.paymentStatus = result.status;
                                             this.ticketUrl = result.ticket_url;
                                         } else {
                                             reject();
                                             alert(result.error || 'Error al procesar el pago.');
                                         }
                                     })
                                     .catch(error => {
                                         console.error(error);
                                         reject();
                                         alert('Ocurrió un error inesperado al enviar el pago.');
                                     });
                             });
                         },
                         onError: (error) => {
                             console.error('Error Brick:', error);
                         }
                     }
                 }
             );
         }
     }"
     x-on:open-mercadopago-modal.window="
         showModal = true;
         paymentStatus = null;
         ticketUrl = null;
         loading = false;
         $nextTick(() => {
             initPaymentBrick($event.detail.preferenceId, $event.detail.total);
         });
     "
     x-on:payment-error.window="loading = false">

    <style>
        /* Custom organic pulsing animations */
        @keyframes organic-pulse {
            0% { transform: scale(0.95); opacity: 0.6; }
            50% { transform: scale(1.05); opacity: 1; }
            100% { transform: scale(0.95); opacity: 0.6; }
        }

        @keyframes ripple-expand {
            0% { transform: scale(0.8); opacity: 0.8; }
            100% { transform: scale(2.5); opacity: 0; }
        }

        .animate-organic {
            animation: organic-pulse 3s ease-in-out infinite;
        }

        .ripple-1 {
            animation: ripple-expand 2.5s cubic-bezier(0.4, 0, 0.2, 1) infinite;
        }

        .ripple-2 {
            animation: ripple-expand 2.5s cubic-bezier(0.4, 0, 0.2, 1) infinite;
            animation-delay: 1.25s;
        }

        .text-shimmer {
            background: linear-gradient(90deg, #4a4e4a 0%, #4a7c59 50%, #4a4e4a 100%);
            background-size: 200% auto;
            color: transparent;
            -webkit-background-clip: text;
            background-clip: text;
            animation: shimmer 3s linear infinite;
        }

        @keyframes shimmer {
            to { background-position: 200% center; }
        }
    </style>

    <!-- Loading Overlay -->
    <div x-show="loading"
         class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-surface overflow-hidden"
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <!-- Main Loading Container -->
        <div class="relative flex flex-col items-center justify-center gap-12 z-10">
            <!-- Animated Logo Wrapper -->
            <div class="relative flex items-center justify-center w-32 h-32">
                <!-- Expanding Ripples -->
                <div class="absolute inset-0 bg-primary-container/20 rounded-full ripple-1"></div>
                <div class="absolute inset-0 bg-primary-container/20 rounded-full ripple-2"></div>
                <!-- Central Solid Core -->
                <div class="relative z-10 w-24 h-24 bg-surface-container-low shadow-[0_4px_20px_rgba(46,50,48,0.06)] rounded-full flex items-center justify-center animate-organic border border-surface-container-high">
                    <div class="w-16 h-16 bg-primary-container/30 rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-4xl text-primary" style="font-variation-settings: 'FILL' 1;">
                            spa
                        </span>
                    </div>
                </div>
            </div>
            <!-- Typography -->
            <div class="text-center space-y-2">
                <h1 class="font-headline text-2xl md:text-3xl font-medium text-shimmer tracking-wide">
                    Destilando tu inteligencia...
                </h1>
                <p class="font-body text-on-surface-variant/70 text-sm md:text-base animate-pulse">
                    Preparando el entorno
                </p>
            </div>
        </div>
        <!-- Atmospheric background elements (subtle gradients to emphasize the center) -->
        <div class="absolute inset-0 pointer-events-none" style="background: radial-gradient(circle at center, transparent 0%, #faf6f0 70%);"></div>
    </div>

    <!-- Header (Transactional, minimal nav) -->
    <header class="mb-10 flex justify-between items-center border-b border-outline-variant/20 pb-6">
        <h1 class="text-3xl font-headline font-bold text-primary flex items-center gap-2">
            <span class="material-symbols-outlined">local_shipping</span>
            Información de Envío
        </h1>
        <a class="flex items-center gap-2 text-secondary hover:text-primary transition-colors font-semibold text-sm" href="{{ route('cart') }}" wire:navigate>
            <span class="material-symbols-outlined text-lg">arrow_back</span>
            Volver al carrito
        </a>
    </header>

    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-error-container text-on-error-container rounded-xl border border-error/20 flex items-center gap-3">
            <span class="material-symbols-outlined text-error">error</span>
            <span class="font-body text-sm font-semibold">{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Left Column: Form & Shipping Methods -->
        <div class="lg:col-span-8 space-y-8">
            <!-- Progress Tracker -->
            <div class="mb-6">
                <nav aria-label="Progress">
                    <ol class="flex items-center" role="list">
                        <li class="relative pr-8 sm:pr-20">
                            <div aria-hidden="true" class="absolute inset-0 flex items-center">
                                <div class="h-0.5 w-full bg-primary"></div>
                            </div>
                            <a class="relative flex h-8 w-8 items-center justify-center rounded-full bg-primary hover:bg-primary-container transition-colors" href="{{ route('cart') }}" wire:navigate>
                                <span class="material-symbols-outlined text-on-primary text-sm filled">check</span>
                            </a>
                            <span class="absolute -bottom-6 left-1/2 -translate-x-1/2 text-xs font-semibold text-primary whitespace-nowrap">Carrito</span>
                        </li>
                        <li class="relative pr-8 sm:pr-20">
                            <div aria-hidden="true" class="absolute inset-0 flex items-center">
                                <div class="h-0.5 w-full bg-surface-variant"></div>
                            </div>
                            <a aria-current="step" class="relative flex h-8 w-8 items-center justify-center rounded-full border-2 border-primary bg-surface-bright" href="#">
                                <span class="h-2.5 w-2.5 rounded-full bg-primary"></span>
                            </a>
                            <span class="absolute -bottom-6 left-1/2 -translate-x-1/2 text-xs font-semibold text-on-surface whitespace-nowrap">Envío</span>
                        </li>
                        <li class="relative">
                            <a class="group relative flex h-8 w-8 items-center justify-center rounded-full border-2 border-surface-variant bg-surface-bright hover:border-outline" href="#">
                                <span class="h-2.5 w-2.5 rounded-full bg-transparent group-hover:bg-outline-variant"></span>
                            </a>
                            <span class="absolute -bottom-6 left-1/2 -translate-x-1/2 text-xs font-semibold text-secondary whitespace-nowrap">Pago</span>
                        </li>
                    </ol>
                </nav>
            </div>

            <!-- Shipping Information Form -->
            <section class="bg-surface-bright rounded-xl p-6 sm:p-8 shadow-[0_4px_20px_rgba(46,50,48,0.06)] border border-outline-variant/30 mt-8">
                <h2 class="text-2xl font-headline font-bold text-on-surface mb-6 flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary">location_on</span>
                    Dirección de entrega
                </h2>
                
                <div class="space-y-6">
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-on-surface-variant mb-1" for="address">Dirección postal (Calle y número)</label>
                            <input autocomplete="street-address" class="block w-full rounded-lg border-0 py-3 text-on-surface shadow-sm ring-1 ring-inset ring-outline-variant/50 placeholder:text-outline focus:ring-2 focus:ring-inset focus:ring-primary bg-surface-container-lowest sm:text-sm sm:leading-6" id="address" wire:model="address" placeholder="Ej: Av. San Martín 1234, Piso 3 Depto B" type="text"/>
                            @error('address') <span class="text-xs text-error mt-1 block font-semibold">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-on-surface-variant mb-1" for="city">Ciudad / Localidad</label>
                            <input autocomplete="address-level2" class="block w-full rounded-lg border-0 py-3 text-on-surface shadow-sm ring-1 ring-inset ring-outline-variant/50 placeholder:text-outline focus:ring-2 focus:ring-inset focus:ring-primary bg-surface-container-lowest sm:text-sm sm:leading-6" id="city" wire:model="city" type="text"/>
                            @error('city') <span class="text-xs text-error mt-1 block font-semibold">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-on-surface-variant mb-1" for="postal-code">Código Postal</label>
                            <input autocomplete="postal-code" class="block w-full rounded-lg border-0 py-3 text-on-surface shadow-sm ring-1 ring-inset ring-outline-variant/50 placeholder:text-outline focus:ring-2 focus:ring-inset focus:ring-primary bg-surface-container-lowest sm:text-sm sm:leading-6" id="postal-code" wire:model="postal_code" placeholder="Ej: 1414" type="text"/>
                            @error('postal_code') <span class="text-xs text-error mt-1 block font-semibold">{{ $message }}</span> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-on-surface-variant mb-1" for="province">Provincia</label>
                            <select autocomplete="address-level1" class="block w-full rounded-lg border-0 py-3 text-on-surface shadow-sm ring-1 ring-inset ring-outline-variant/50 focus:ring-2 focus:ring-inset focus:ring-primary bg-surface-container-lowest sm:text-sm sm:leading-6" id="province" wire:model="province">
                                <option value="">Selecciona tu provincia</option>
                                <option>Buenos Aires</option>
                                <option>Ciudad Autónoma de Buenos Aires</option>
                                <option>Catamarca</option>
                                <option>Chaco</option>
                                <option>Chubut</option>
                                <option>Córdoba</option>
                                <option>Corrientes</option>
                                <option>Entre Ríos</option>
                                <option>Formosa</option>
                                <option>Jujuy</option>
                                <option>La Pampa</option>
                                <option>La Rioja</option>
                                <option>Mendoza</option>
                                <option>Misiones</option>
                                <option>Neuquén</option>
                                <option>Río Negro</option>
                                <option>Salta</option>
                                <option>San Juan</option>
                                <option>San Luis</option>
                                <option>Santa Cruz</option>
                                <option>Santa Fe</option>
                                <option>Santiago del Estero</option>
                                <option>Tierra del Fuego</option>
                                <option>Tucumán</option>
                            </select>
                            @error('province') <span class="text-xs text-error mt-1 block font-semibold">{{ $message }}</span> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-on-surface-variant mb-1" for="phone">Teléfono de contacto</label>
                            <div class="relative mt-2 rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-on-surface-variant sm:text-sm">+54</span>
                                </div>
                                <input autocomplete="tel" class="block w-full rounded-lg border-0 py-3 pl-12 text-on-surface shadow-sm ring-1 ring-inset ring-outline-variant/50 placeholder:text-outline focus:ring-2 focus:ring-inset focus:ring-primary bg-surface-container-lowest sm:text-sm sm:leading-6" id="phone" wire:model="phone" placeholder="Cod. área + Número" type="tel"/>
                            </div>
                            @error('phone') <span class="text-xs text-error mt-1 block font-semibold">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </section>

            <!-- Shipping Methods -->
            <section class="bg-surface-bright rounded-xl p-6 sm:p-8 shadow-[0_4px_20px_rgba(46,50,48,0.06)] border border-outline-variant/30">
                <h2 class="text-2xl font-headline font-bold text-on-surface mb-6 flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary">local_shipping</span>
                    Método de envío
                </h2>
                
                <fieldset class="space-y-4">
                    <legend class="sr-only">Selecciona un método de envío</legend>
                    
                    <!-- Option 1 -->
                    <label class="relative flex cursor-pointer rounded-xl border p-4 shadow-sm focus:outline-none hover:bg-surface transition-all duration-200"
                           :class="$wire.shipping_method === 'standard' ? 'border-primary bg-surface-container-lowest' : 'border-outline-variant/55 bg-surface-container-lowest/50'">
                        <input class="mt-1 h-4 w-4 shrink-0 cursor-pointer border-outline-variant text-primary focus:ring-primary" name="shipping-method" type="radio" wire:model.live="shipping_method" value="standard"/>
                        <span class="ml-4 flex flex-col w-full">
                            <span class="flex justify-between items-center">
                                <span class="block text-sm font-semibold text-on-surface">Envío a Domicilio</span>
                                <span class="text-sm font-bold text-on-surface">$4.500</span>
                            </span>
                            <span class="mt-1 flex items-center text-sm text-secondary gap-1">
                                <span class="material-symbols-outlined text-[16px]">info</span>
                                Correo Argentino / Andreani. Entrega en 3 a 5 días hábiles.
                            </span>
                        </span>
                    </label>

                    <!-- Option 2 -->
                    <label class="relative flex cursor-pointer rounded-xl border p-4 shadow-sm focus:outline-none hover:bg-surface transition-all duration-200"
                           :class="$wire.shipping_method === 'express' ? 'border-primary bg-surface-container-lowest' : 'border-outline-variant/55 bg-surface-container-lowest/50'">
                        <input class="mt-1 h-4 w-4 shrink-0 cursor-pointer border-outline-variant text-primary focus:ring-primary" name="shipping-method" type="radio" wire:model.live="shipping_method" value="express"/>
                        <span class="ml-4 flex flex-col w-full">
                            <span class="flex justify-between items-center">
                                <span class="block text-sm font-semibold text-on-surface flex items-center gap-2">
                                    Envío Express (Motos)
                                    <span class="inline-flex items-center rounded-full bg-tertiary-fixed px-2 py-0.5 text-xs font-medium text-on-tertiary-fixed-variant">AMBA Solo</span>
                                </span>
                                <span class="text-sm font-bold text-on-surface">$6.000</span>
                            </span>
                            <span class="mt-1 flex items-center text-sm text-secondary gap-1">
                                <span class="material-symbols-outlined text-[16px]">two_wheeler</span>
                                Entrega en el día (comprando antes de las 13hs).
                            </span>
                        </span>
                    </label>

                    <!-- Option 3 -->
                    <label class="relative flex cursor-pointer rounded-xl border p-4 shadow-sm focus:outline-none hover:bg-surface transition-all duration-200"
                           :class="$wire.shipping_method === 'pickup' ? 'border-primary bg-surface-container-lowest' : 'border-outline-variant/55 bg-surface-container-lowest/50'">
                        <input class="mt-1 h-4 w-4 shrink-0 cursor-pointer border-outline-variant text-primary focus:ring-primary" name="shipping-method" type="radio" wire:model.live="shipping_method" value="pickup"/>
                        <span class="ml-4 flex flex-col w-full">
                            <span class="flex justify-between items-center">
                                <span class="block text-sm font-semibold text-on-surface">Retiro en Punto de Venta</span>
                                <span class="text-sm font-bold text-primary">Gratis</span>
                            </span>
                            <span class="mt-1 flex items-center text-sm text-secondary gap-1">
                                <span class="material-symbols-outlined text-[16px]">storefront</span>
                                Av. Santa Fe 3200, Palermo. Lunes a Viernes 10 a 18hs.
                            </span>
                        </span>
                    </label>
                </fieldset>
                @error('shipping_method') <span class="text-xs text-error mt-1 block font-semibold">{{ $message }}</span> @enderror
            </section>
        </div>

        <!-- Right Column: Order Summary -->
        <div class="lg:col-span-4">
            <div class="bg-surface-container-low rounded-xl p-6 sticky top-24 shadow-[0_4px_20px_rgba(46,50,48,0.06)] border border-outline-variant/20">
                <h3 class="text-lg font-headline font-semibold text-on-surface mb-6 border-b border-outline-variant/30 pb-4">Resumen de tu pedido</h3>
                
                <!-- Items list -->
                <ul class="divide-y divide-outline-variant/30 mb-6" role="list">
                    @php $hasStockErrors = false; @endphp
                    @foreach($items as $item)
                    @php if(!empty($item['has_stock_error'])) $hasStockErrors = true; @endphp
                    <li class="flex py-4">
                        <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-md border border-outline-variant/20 bg-surface-bright">
                            @if(isset($item['img']))
                                <img alt="{{ $item['name'] }}" class="h-full w-full object-cover object-center {{ !empty($item['has_stock_error']) ? 'opacity-50 grayscale' : '' }}" src="{{ $item['img'] }}"/>
                            @else
                                <div class="h-full w-full flex items-center justify-center bg-surface-container">
                                    <span class="material-symbols-outlined text-outline-variant text-2xl">local_florist</span>
                                </div>
                            @endif
                        </div>
                        <div class="ml-4 flex flex-1 flex-col">
                            <div>
                                <div class="flex justify-between text-sm font-medium text-on-surface">
                                    <h4 class="font-bold {{ !empty($item['has_stock_error']) ? 'text-error' : '' }}">{{ $item['name'] }}</h4>
                                    @if(!empty($item['has_stock_error']))
                                        <p class="ml-4 font-bold text-outline-variant line-through">${{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</p>
                                    @else
                                        <p class="ml-4 font-bold text-primary">${{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</p>
                                    @endif
                                </div>
                                <p class="mt-1 text-xs text-secondary">{{ $item['size'] ?? 'Decant 10ml' }} · {{ $item['type'] ?? 'Aroma' }}</p>
                                @if(!empty($item['has_stock_error']))
                                    <span class="text-[10px] text-error font-bold block mt-1">Stock insuficiente</span>
                                @endif
                            </div>
                            <div class="flex flex-1 items-end justify-between text-xs mt-2">
                                <p class="text-secondary">Cant: {{ $item['quantity'] }}</p>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>

                @if ($hasStockErrors)
                    <div class="p-4 bg-error-container/20 border border-error/30 rounded-xl text-center mb-6">
                        <p class="text-xs text-error font-bold uppercase tracking-wider mb-1">Ajuste de Stock</p>
                        <p class="text-xs text-on-surface-variant leading-normal mb-3">Los productos con stock insuficiente no se incluirán en el pago.</p>
                        <a href="{{ route('cart') }}" wire:navigate class="inline-block w-full py-2 bg-error text-on-error hover:bg-error/90 text-xs font-bold rounded-lg transition-all shadow-sm">
                            Modificar carrito
                        </a>
                    </div>
                @endif

                <!-- Totals -->
                <dl class="space-y-4 text-sm text-on-surface-variant">
                    <div class="flex items-center justify-between">
                        <dt>Subtotal</dt>
                        <dd class="font-medium text-on-surface">${{ number_format($this->subtotal, 0, ',', '.') }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt>Envío estimado</dt>
                        <dd class="font-medium text-on-surface">
                            {{ $this->shippingCost === 0 ? 'Gratis' : '$' . number_format($this->shippingCost, 0, ',', '.') }}
                        </dd>
                    </div>
                    <div class="flex items-center justify-between border-t border-outline-variant/30 pt-4 text-base font-bold text-on-surface">
                        <dt>Total</dt>
                        <dd class="text-primary text-lg font-bold">${{ number_format($this->total, 0, ',', '.') }}</dd>
                    </div>
                </dl>

                <div class="mt-8">
                    <button wire:click="proceedToPayment" x-on:click="loading = true" class="w-full rounded-xl bg-primary px-4 py-4 text-base font-bold text-on-primary shadow-sm hover:bg-surface-tint focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-all active:scale-[0.98] flex items-center justify-center gap-2" type="button">
                        Continuar al Pago
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </button>
                    <p class="mt-4 flex items-center justify-center gap-2 text-xs text-secondary text-center">
                        <span class="material-symbols-outlined text-[16px]">lock</span>
                        Proceso de pago seguro y encriptado
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal (Mercado Pago Bricks) -->
    <div x-data=""
         x-show="showModal"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm px-4"
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">
        <div class="bg-surface rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl border border-outline-variant/30 flex flex-col max-h-[90vh]"
             x-on:click.away="if (!paymentStatus && !loading) showModal = false">
            <div class="p-5 border-b border-outline-variant/20 flex justify-between items-center bg-surface-container-low">
                <h3 class="text-xl font-headline font-bold text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">security</span>
                    Pago Seguro
                </h3>
                <button x-show="!paymentStatus" x-on:click="showModal = false" class="text-outline hover:text-on-surface transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <div class="p-6 overflow-y-auto flex-grow bg-surface">
                <!-- Success/Ticket State -->
                <div x-show="paymentStatus" class="text-center py-6 flex flex-col items-center gap-4" style="display: none;">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mb-2"
                         :class="paymentStatus === 'approved' ? 'bg-success/10 text-success' : 'bg-warning/10 text-warning'">
                         <span class="material-symbols-outlined text-4xl" x-text="paymentStatus === 'approved' ? 'check_circle' : 'pending'"></span>
                    </div>
                    
                    <h4 class="text-2xl font-headline font-bold text-on-surface" 
                        x-text="paymentStatus === 'approved' ? '¡Pago Aprobado!' : 'Pago Pendiente'">
                    </h4>
                    
                    <p class="text-on-surface-variant max-w-sm text-sm"
                       x-text="paymentStatus === 'approved' ? 'Tu compra en Esencia ha sido confirmada. Pronto recibirás un correo con la información de envío de tus decants.' : 'Tu cupón de pago ha sido generado correctamente. Realiza el pago para finalizar la compra.'">
                    </p>
                    
                    <div x-show="ticketUrl" class="mt-4 w-full">
                        <a :href="ticketUrl" target="_blank" class="w-full bg-primary text-on-primary py-3 px-4 rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-opacity-95 transition-all shadow-md">
                            <span class="material-symbols-outlined">receipt_long</span>
                            Ver Cupón de Pago
                        </a>
                    </div>
                    
                    <button x-on:click="window.location.reload()" class="mt-6 bg-surface-container-highest text-primary font-bold py-2.5 px-6 rounded-lg border border-primary/20 hover:bg-primary hover:text-on-primary transition-all">
                        Volver a la tienda
                    </button>
                </div>

                <!-- Brick Container -->
                <div x-show="!paymentStatus">
                    <div id="paymentBrick_container"></div>
                </div>
            </div>
        </div>
    </div>
</div>
