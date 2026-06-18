<div>
    {{-- Stepper --}}
    <div class="max-w-4xl mx-auto mb-10 px-4">
        <div class="relative flex items-center justify-between">
            <div class="absolute left-0 right-0 top-1/2 h-1 bg-gray-100 -translate-y-1/2 rounded-full z-0"></div>
            <div class="absolute left-0 top-1/2 h-1 bg-purple-600 -translate-y-1/2 rounded-full transition-all duration-500 z-0"
                 style="width: {{ $step == 1 ? '16%' : ($step == 2 ? '50%' : '100%') }}"></div>
            @foreach([1=>'Envío',2=>'Pago',3=>'Confirmación'] as $n => $label)
            <div class="relative z-10 flex flex-col items-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all {{ $step >= $n ? 'bg-purple-600 text-white ring-4 ring-purple-100' : 'bg-gray-100 text-gray-400' }}">
                    @if($step > $n)<i class="fas fa-check"></i>@else{{ $n }}@endif
                </div>
                <span class="text-xs font-bold mt-2 {{ $step >= $n ? 'text-purple-600' : 'text-gray-400' }}">{{ $label }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4">
        @if($step == 3)
            {{-- STEP 3: CONFIRMACIÓN --}}
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden mb-12">
                <div class="bg-gradient-to-r from-purple-600 to-indigo-700 py-12 px-8 text-center text-white">
                    <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg animate-bounce ring-4 ring-green-500/30">
                        <i class="fas fa-check text-4xl text-white"></i>
                    </div>
                    <h2 class="text-3xl font-black mb-2">¡Pago Confirmado!</h2>
                    <p class="text-purple-100 text-sm">Gracias por tu compra. Tu pedido está en camino.</p>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-b border-gray-100 pb-8 mb-8">
                        <div>
                            <h3 class="text-xs font-black uppercase text-gray-400 tracking-wider mb-2">Detalles del Pedido</h3>
                            <p class="text-lg font-bold text-gray-800">Pedido #{{ str_pad($createdOrder->id,6,'0',STR_PAD_LEFT) }}</p>
                            <p class="text-xs text-gray-500">Fecha: {{ $createdOrder->created_at->format('d/m/Y H:i') }} hs</p>
                        </div>
                        <div>
                            <h3 class="text-xs font-black uppercase text-gray-400 tracking-wider mb-2">Dirección de Entrega</h3>
                            <p class="text-sm font-bold text-gray-800">{{ $createdOrder->shipping_address['contact'] }}</p>
                            <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                                {{ $createdOrder->shipping_address['address'] }} {{ $createdOrder->shipping_address['apartment'] }}<br>
                                {{ $createdOrder->shipping_address['locality'] }} ({{ $createdOrder->shipping_address['zip_code'] }})<br>
                                {{ $createdOrder->shipping_address['province'] }}
                            </p>
                        </div>
                    </div>
                    <div class="space-y-4 mb-8">
                        @foreach($createdOrder->items as $item)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center font-bold text-purple-700">{{ $item->quantity }}x</div>
                                <p class="text-sm font-bold text-gray-800">{{ $item->name }}</p>
                            </div>
                            <span class="font-extrabold text-gray-800">{{ cart_format_price($item->price * $item->quantity) }}</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="bg-purple-50 border border-purple-100 rounded-3xl p-6 mb-8">
                        <div class="flex justify-between text-sm text-gray-600 mb-2"><span>Subtotal</span><span class="font-bold">{{ cart_format_price($createdOrder->subtotal) }}</span></div>
                        <div class="flex justify-between text-sm text-gray-600 mb-2"><span>Envío</span><span class="text-green-600 font-bold">Gratis</span></div>
                        <div class="pt-4 border-t border-purple-100 flex justify-between">
                            <span class="font-black text-gray-800 text-lg">Total</span>
                            <span class="text-2xl font-black text-purple-600">{{ cart_format_price($createdOrder->total) }}</span>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4 justify-between">
                        <button onclick="window.print()" class="px-6 py-3 border-2 border-purple-200 text-purple-600 font-bold rounded-2xl hover:bg-purple-50 transition-all flex items-center gap-2">
                            <i class="fas fa-print"></i> Imprimir Comprobante
                        </button>
                        <a href="{{ url(config('katrix-cart.home_route', '/')) }}" class="px-8 py-4 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-2xl transition-all text-center">
                            Volver a la Tienda <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

        @elseif($step == 1)
            {{-- STEP 1: ENVÍO --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                    <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-purple-500"></i> Dirección de Envío
                    </h2>
                    @if(!$newAddress)
                    <button wire:click="$set('newAddress',true)" class="text-xs bg-purple-600 text-white px-4 py-2.5 rounded-xl font-bold hover:bg-purple-700 transition-all">
                        <i class="fas fa-plus mr-1"></i> Nueva dirección
                    </button>
                    @endif
                </div>
                <div class="p-6">
                    @if($newAddress)
                    <div class="bg-gray-50/50 rounded-3xl p-6 mb-8 border border-gray-100">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-bold text-gray-800">{{ $form->addressId ? 'Editar' : 'Nueva' }} Dirección</h3>
                            <button wire:click="$set('newAddress',false)" class="w-8 h-8 rounded-full bg-white border border-gray-100 flex items-center justify-center text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <form wire:submit.prevent="saveAddress" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                            <div class="md:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                                <select wire:model="form.type" class="w-full border-gray-200 rounded-xl text-sm">
                                    <option>Hogar</option><option>Trabajo</option><option>Otro</option>
                                </select>
                            </div>
                            <div class="md:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Provincia</label>
                                <select wire:model.live="form.province" class="w-full border-gray-200 rounded-xl text-sm">
                                    <option value="">Selecciona provincia</option>
                                    @foreach(['Buenos Aires','Capital Federal','Catamarca','Chaco','Chubut','Córdoba','Corrientes','Entre Ríos','Formosa','Jujuy','La Pampa','La Rioja','Mendoza','Misiones','Neuquén','Río Negro','Salta','San Juan','San Luis','Santa Cruz','Santa Fe','Santiago del Estero','Tierra del Fuego','Tucumán'] as $prov)
                                    <option>{{ $prov }}</option>
                                    @endforeach
                                </select>
                                @error('form.province')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>
                            <div class="md:col-span-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Localidad</label>
                                <select wire:model.live="form.locality" class="w-full border-gray-200 rounded-xl text-sm" {{ empty($localities) ? 'disabled' : '' }}>
                                    <option value="">{{ empty($localities) ? 'Selecciona primero una provincia' : 'Selecciona localidad' }}</option>
                                    @foreach($localities as $loc)<option value="{{ $loc['nombre'] }}">{{ $loc['nombre'] }}</option>@endforeach
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Código Postal</label>
                                <input type="text" wire:model="form.zip_code" class="w-full border-gray-200 rounded-xl text-sm px-3 py-2" placeholder="Ej: 5500">
                            </div>
                            <div class="md:col-span-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                                <input type="text" wire:model="form.address" class="w-full border-gray-200 rounded-xl text-sm px-3 py-2" placeholder="Calle y número">
                                @error('form.address')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Piso/Depto</label>
                                <input type="text" wire:model="form.apartment" class="w-full border-gray-200 rounded-xl text-sm px-3 py-2" placeholder="4B">
                            </div>
                            <div class="md:col-span-6">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono de contacto</label>
                                <input type="text" wire:model="form.phone" class="w-full border-gray-200 rounded-xl text-sm px-3 py-2" placeholder="11 1234 5678">
                                @error('form.phone')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>
                            <div class="md:col-span-6 pt-4 flex gap-4">
                                <button type="button" wire:click="$set('newAddress',false)" class="flex-1 py-3 text-sm font-bold border border-gray-200 rounded-xl text-gray-600">Cancelar</button>
                                <button type="submit" class="flex-1 py-3 text-sm font-bold bg-purple-600 text-white rounded-xl">{{ $form->addressId ? 'Actualizar' : 'Guardar' }}</button>
                            </div>
                        </form>
                    </div>
                    @endif
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($addresses as $addr)
                        <label class="relative block cursor-pointer group">
                            <input type="radio" name="selectedAddressId" value="{{ $addr->id }}" wire:model.live="selectedAddressId" class="peer sr-only">
                            <div class="h-full p-5 rounded-2xl border-2 border-gray-100 peer-checked:border-purple-600 peer-checked:bg-purple-50/20 transition-all hover:border-gray-200 shadow-sm">
                                <div class="flex justify-between items-start mb-3">
                                    <span class="px-2 py-0.5 rounded-lg bg-gray-100 text-[10px] font-black uppercase text-gray-500">{{ $addr->type }}</span>
                                    @if($addr->is_default)<span class="text-[10px] text-purple-600 font-bold">PREDETERMINADA</span>@endif
                                </div>
                                <p class="font-bold text-gray-800 mb-1">{{ $addr->description ?: $addr->address }}</p>
                                <p class="text-xs text-gray-500 leading-relaxed">{{ $addr->address }}<br>{{ $addr->locality }} ({{ $addr->zip_code }}), {{ $addr->province }}</p>
                                <div class="flex items-center gap-2 text-[10px] text-gray-400 border-t border-gray-100 pt-3 mt-3">
                                    <span>{{ $addr->contact }}</span> • <span>{{ $addr->phone }}</span>
                                    <div class="ml-auto flex gap-3">
                                        <button type="button" wire:click.stop="edit({{ $addr->id }})" class="text-blue-500"><i class="fas fa-edit"></i></button>
                                        <button type="button" onclick="confirmDeleteAddress({{ $addr->id }})" class="text-red-400"><i class="fas fa-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </label>
                        @empty
                        @if(!$newAddress)
                        <div class="col-span-2 py-16 text-center bg-gray-50/50 rounded-3xl border-2 border-dashed border-gray-200">
                            <p class="text-gray-500 font-medium">No tienes direcciones registradas</p>
                            <button wire:click="$set('newAddress',true)" class="mt-4 bg-purple-600 text-white font-bold px-6 py-2.5 rounded-xl text-sm">Agregar dirección</button>
                        </div>
                        @endif
                        @endforelse
                    </div>
                </div>
            </div>

            @include('cart::livewire.partials.checkout-summary')

            <div class="flex justify-end pt-4">
                <button wire:click="goToPayment"
                        {{ !$hasValidItems ? 'disabled' : '' }}
                        @style(['opacity:0.5;cursor:not-allowed;pointer-events:none;' => !$hasValidItems])
                        class="px-10 py-4 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-2xl transition-all shadow-lg flex items-center gap-3">
                    Continuar al pago <i class="fas fa-arrow-right"></i>
                </button>
            </div>

        @elseif($step == 2)
            {{-- STEP 2: PAGO --}}
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                <div class="lg:col-span-2 lg:sticky lg:top-8">
                    @include('cart::livewire.partials.checkout-summary')
                </div>
                <div class="lg:col-span-3 space-y-6">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-5 border-b border-gray-50 bg-gray-50/50">
                            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                <i class="fas fa-credit-card text-purple-500"></i> Método de Pago
                            </h2>
                        </div>
                        <div class="p-5">
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-6">
                                @if(in_array('mercadopago', $paymentMethods))
                                <label class="block cursor-pointer">
                                    <input type="radio" name="paymentMethod" value="mercadopago" wire:model.live="paymentMethod" class="peer sr-only">
                                    <div class="p-4 rounded-2xl border-2 border-gray-100 peer-checked:border-purple-600 peer-checked:bg-purple-50/20 text-center hover:border-gray-200 transition-all">
                                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-2"><i class="fas fa-credit-card text-purple-600 text-sm"></i></div>
                                        <p class="font-bold text-gray-800 text-xs">Mercado Pago</p>
                                        <p class="text-[9px] text-gray-400 mt-0.5">Tarjetas o saldo</p>
                                    </div>
                                </label>
                                @endif
                                @if(in_array('bank_transfer', $paymentMethods))
                                <label class="block cursor-pointer">
                                    <input type="radio" name="paymentMethod" value="bank_transfer" wire:model.live="paymentMethod" class="peer sr-only">
                                    <div class="p-4 rounded-2xl border-2 border-gray-100 peer-checked:border-purple-600 peer-checked:bg-purple-50/20 text-center hover:border-gray-200 transition-all">
                                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-2"><i class="fas fa-university text-purple-600 text-sm"></i></div>
                                        <p class="font-bold text-gray-800 text-xs">Transferencia</p>
                                    </div>
                                </label>
                                @endif
                                @if(in_array('cash', $paymentMethods))
                                <label class="block cursor-pointer">
                                    <input type="radio" name="paymentMethod" value="cash" wire:model.live="paymentMethod" class="peer sr-only">
                                    <div class="p-4 rounded-2xl border-2 border-gray-100 peer-checked:border-purple-600 peer-checked:bg-purple-50/20 text-center hover:border-gray-200 transition-all">
                                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-2"><i class="fas fa-money-bill-wave text-purple-600 text-sm"></i></div>
                                        <p class="font-bold text-gray-800 text-xs">Efectivo</p>
                                    </div>
                                </label>
                                @endif
                            </div>

                            @if($paymentMethod === 'mercadopago')
                            <div wire:ignore class="mt-4" x-data="{init(){const t=()=>{if(typeof window.initMercadoPago==='function')window.initMercadoPago();else setTimeout(t,200)};t();}}">
                                <div id="paymentBrick_container"></div>
                            </div>
                            @elseif($paymentMethod === 'bank_transfer')
                            <div class="space-y-4">
                                <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4">
                                    <h3 class="font-bold text-gray-800 mb-3">Datos Bancarios</h3>
                                    <div class="text-sm space-y-2">
                                        <div class="flex justify-between"><span class="text-gray-500">Banco</span><span class="font-semibold">{{ config('katrix-cart.bank_info.bank') }}</span></div>
                                        <div class="flex justify-between"><span class="text-gray-500">CBU</span><span class="font-semibold select-all">{{ config('katrix-cart.bank_info.cbu') }}</span></div>
                                        <div class="flex justify-between"><span class="text-gray-500">Alias</span><span class="font-semibold select-all">{{ config('katrix-cart.bank_info.alias') }}</span></div>
                                        <div class="flex justify-between"><span class="text-gray-500">Titular</span><span class="font-semibold">{{ config('katrix-cart.bank_info.titular') }}</span></div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-700 mb-1">Nombre del titular que transfiere</label>
                                    <input type="text" wire:model="transfer_issuer_name" class="w-full border-gray-300 rounded-lg text-sm px-3 py-2" placeholder="Juan Pérez">
                                    @error('transfer_issuer_name')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-700 mb-1">CUIT/CUIL</label>
                                    <input type="text" wire:model="transfer_issuer_cuit" class="w-full border-gray-300 rounded-lg text-sm px-3 py-2" placeholder="XX-XXXXXXXX-X">
                                    @error('transfer_issuer_cuit')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                                </div>
                                <div class="border-2 border-dashed border-purple-400 bg-purple-50 rounded-2xl p-6 text-center relative">
                                    <input type="file" wire:model="transfer_receipt" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept=".jpg,.jpeg,.png,.pdf">
                                    @if($transfer_receipt)
                                        <span class="font-bold text-green-600">✓ {{ $transfer_receipt->getClientOriginalName() }}</span>
                                    @else
                                        <p class="font-bold text-gray-700 text-sm">Adjuntar comprobante (PDF o imagen)</p>
                                    @endif
                                    @error('transfer_receipt')<span class="text-red-500 text-xs block mt-2">{{ $message }}</span>@enderror
                                </div>
                                <button wire:click="placeOrder" {{ !$hasValidItems ? 'disabled' : '' }}
                                        class="w-full py-4 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl transition-all disabled:opacity-50">
                                    <span wire:loading.remove wire:target="placeOrder">Confirmar Pedido</span>
                                    <span wire:loading wire:target="placeOrder"><i class="fas fa-spinner fa-spin"></i> Procesando...</span>
                                </button>
                            </div>
                            @elseif($paymentMethod === 'cash')
                            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6">
                                <h3 class="font-bold text-gray-800 flex items-center gap-2 mb-3">
                                    <i class="fas fa-money-bill-wave text-purple-600"></i> Pago contra entrega
                                </h3>
                                <p class="text-sm text-gray-600">Pagás al repartidor en efectivo cuando recibís el pedido.</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    @if($paymentMethod !== 'bank_transfer')
                    <div class="flex justify-between pt-2">
                        <button wire:click="backToShipping" class="px-6 py-3 border-2 border-purple-200 text-purple-600 font-bold rounded-2xl hover:bg-purple-50 transition-all flex items-center gap-2">
                            <i class="fas fa-arrow-left"></i> Volver
                        </button>
                        @if($paymentMethod === 'cash')
                        <button wire:click="placeOrder" {{ !$hasValidItems ? 'disabled' : '' }}
                                class="px-8 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-2xl transition-all shadow-lg disabled:opacity-50">
                            <span wire:loading.remove wire:target="placeOrder">Confirmar Pedido <i class="fas fa-check"></i></span>
                            <span wire:loading wire:target="placeOrder"><i class="fas fa-spinner fa-spin"></i> Procesando...</span>
                        </button>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    @push('js')
    <script>
        function confirmDeleteAddress(id) {
            if(typeof Swal !== 'undefined') {
                Swal.fire({ title:'¿Eliminar dirección?', icon:'warning', showCancelButton:true, confirmButtonColor:'#7c3aed', cancelButtonColor:'#9ca3af', confirmButtonText:'Sí, eliminar', cancelButtonText:'Cancelar' })
                .then((r) => { if(r.isConfirmed) @this.delete(id); });
            } else if(confirm('¿Eliminar esta dirección?')) {
                @this.delete(id);
            }
        }
    </script>
    @if($paymentMethod === 'mercadopago' && $step == 2)
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            let brickController = null;
            window.initMercadoPago = async () => {
                if(brickController) brickController.unmount();
                const mp = new MercadoPago('{{ $mpPublicKey }}', { locale: '{{ config("katrix-cart.mercadopago.locale","es-AR") }}' });
                const settings = {
                    initialization: { amount: {{ $totalAmount ?? 0 }} },
                    customization: { paymentMethods: { creditCard:"all", debitCard:"all", ticket:"all" } },
                    callbacks: {
                        onReady: () => {},
                        onSubmit: ({ formData }) => new Promise((resolve, reject) => {
                            fetch('{{ route("mercadopago.process") }}', {
                                method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
                                body: JSON.stringify(formData)
                            })
                            .then(r => r.json())
                            .then(r => { resolve(); if(r.id && ['approved','in_process','pending'].includes(r.status)) { Livewire.dispatch('mpPaymentApproved',{mpPaymentId:r.id,mpStatus:r.status,mpPaymentType:r.payment_type_id}); } else { alert(r.error || 'Pago rechazado.'); } })
                            .catch(() => { reject(); alert('Error de conexión con Mercado Pago.'); });
                        }),
                        onError: (e) => console.error('MP Error:',e)
                    }
                };
                try { brickController = await mp.bricks().create("payment","paymentBrick_container",settings); } catch(e) { console.error(e); }
            };
        });
    </script>
    @endif
    @endpush
</div>
