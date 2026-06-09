<div>
    <livewire:hero />

    <section class="max-w-7xl w-full mx-auto px-6 py-12 flex flex-col md:flex-row gap-12">
        <!-- Filters Sidebar -->
        <aside class="w-full md:w-64 flex-shrink-0">
            <div class="sticky top-28 bg-surface-container-lowest rounded-2xl p-6 shadow-[0_4px_20px_rgba(46,50,48,0.03)] border border-surface-container-highest">
                <h2 class="font-headline text-xl text-on-background mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">tune</span>
                    Filtros
                </h2>
                
                <div class="mb-8">
                    <h3 class="text-sm font-bold text-on-surface-variant mb-4 uppercase tracking-wider">Familia Olfativa</h3>
                    <div class="flex flex-col gap-3">
                        @php
                            $families = ['Amaderado', 'Floral Blanco', 'Cítrico Terroso', 'Oriental Especiado'];
                        @endphp
                        @foreach($families as $family)
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" wire:model.live="selectedFamilies" value="{{ $family }}" class="w-5 h-5 rounded border-outline-variant text-primary focus:ring-primary focus:ring-offset-surface-container-lowest bg-surface transition-all" />
                            <span class="text-on-background group-hover:text-primary transition-colors">{{ $family }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-on-surface-variant mb-4 uppercase tracking-wider">Formato</h3>
                    <div class="flex flex-wrap gap-2">
                        <button class="px-4 py-2 bg-secondary-container text-on-secondary-container rounded-lg text-sm hover:bg-primary-container hover:text-on-primary-container transition-colors">Decant 5ml</button>
                        <button class="px-4 py-2 bg-secondary-container text-on-secondary-container rounded-lg text-sm hover:bg-primary-container hover:text-on-primary-container transition-colors">Decant 10ml</button>
                        <button class="px-4 py-2 bg-surface border border-outline-variant text-on-surface-variant rounded-lg text-sm hover:border-primary hover:text-primary transition-colors">Botella 50ml</button>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Product Grid -->
        <div class="flex-grow grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($this->products as $product)
                <livewire:product-card :product="$product" :key="$product['id']" />
            @endforeach
        </div>
    </section>
</div>
