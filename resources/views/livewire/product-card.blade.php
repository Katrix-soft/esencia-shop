<article class="bg-surface-container-lowest rounded-2xl overflow-hidden shadow-[0_4px_20px_rgba(46,50,48,0.04)] border border-surface-container flex flex-col group transition-transform duration-300 hover:-translate-y-1">
    <div class="aspect-[4/5] w-full bg-surface-container-low relative overflow-hidden">
        <img alt="{{ $product->name ?? 'Producto' }}" class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500" src="{{ $product->image ?? '' }}" />
        
        @if(!empty($product->badge))
        <div class="absolute top-4 right-4 bg-surface-container-lowest/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-tertiary shadow-sm">
            {{ $product->badge }}
        </div>
        @endif
    </div>
    <div class="p-6 flex flex-col flex-grow">
        @if(!empty($product->category) || !empty($product->audience) || !empty($product->brand) || !empty($product->gender) || !empty($product->longevity))
        <div class="flex flex-wrap gap-2 mb-3">
            @if(!empty($product->brand))
            <span class="px-2 py-1 bg-surface-container-high text-on-surface rounded text-xs font-bold border border-surface-container-highest">{{ $product->brand }}</span>
            @endif
            @if(!empty($product->category))
            <span class="px-2 py-1 {{ $product->category->color_class ?? 'bg-surface-container text-on-surface-variant' }} rounded text-xs font-semibold">{{ $product->category->name }}</span>
            @endif
            @if(!empty($product->audience))
            <span class="px-2 py-1 bg-surface-container text-on-surface-variant rounded text-xs">{{ $product->audience }}</span>
            @endif
            @if(!empty($product->gender))
            <span class="px-2 py-1 bg-surface-container text-on-surface-variant rounded text-xs capitalize">{{ $product->gender }}</span>
            @endif
            @if(!empty($product->longevity))
            <span class="px-2 py-1 bg-surface-container text-on-surface-variant rounded text-xs flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">schedule</span> {{ $product->longevity }}
            </span>
            @endif
        </div>
        @endif
        <h3 class="font-headline text-xl text-on-background mb-1">{{ $product->name ?? 'Producto sin nombre' }}</h3>
        <p class="text-sm text-secondary mb-4 line-clamp-2 leading-relaxed">{{ $product->description ?? '' }}</p>
        
        <!-- Fragella Headless Widget -->
        <div class="fragella-inject-accords mb-4" data-fragrance-name="{{ $product->name }}"></div>
        
        <div class="mt-auto pt-4 border-t border-surface-container-highest flex items-center justify-between">
            <span class="font-headline font-bold text-lg text-primary">${{ number_format(($product->price ?? 0) * 1000, 0, ',', '.') }}</span>
            <button wire:click="addToCart" class="bg-primary text-on-primary hover:bg-primary/90 px-4 py-2 rounded-xl text-sm font-bold transition-colors">
                Añadir al carrito
            </button>
        </div>
    </div>
</article>
