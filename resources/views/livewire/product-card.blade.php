<article class="bg-surface-container-lowest rounded-2xl overflow-hidden shadow-[0_4px_20px_rgba(46,50,48,0.04)] border border-surface-container flex flex-col group transition-transform duration-300 hover:-translate-y-1">
    <div class="aspect-[4/5] w-full bg-surface-container-low relative overflow-hidden">
        <img alt="{{ $product['name'] }}" class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500" src="{{ $product['image'] }}" />
        
        @if($product['badge'])
        <div class="absolute top-4 right-4 bg-surface-container-lowest/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-tertiary shadow-sm">
            {{ $product['badge'] }}
        </div>
        @endif
    </div>
    <div class="p-6 flex flex-col flex-grow">
        <div class="flex gap-2 mb-3">
            <span class="px-2 py-1 {{ $product['family_class'] }} rounded text-xs font-semibold">{{ $product['family'] }}</span>
            <span class="px-2 py-1 bg-surface-container text-on-surface-variant rounded text-xs">{{ $product['audience'] }}</span>
        </div>
        <h3 class="font-headline text-xl text-on-background mb-1">{{ $product['name'] }}</h3>
        <p class="text-sm text-secondary mb-4 line-clamp-2 leading-relaxed">{{ $product['description'] }}</p>
        
        <div class="mt-auto pt-4 border-t border-surface-container-highest flex items-center justify-between">
            <span class="font-headline font-bold text-lg text-primary">${{ number_format($product['price'] * 1000, 0, ',', '.') }}</span>
            <button wire:click="addToCart" class="bg-primary text-on-primary hover:bg-primary/90 px-4 py-2 rounded-xl text-sm font-bold transition-colors">
                Añadir al carrito
            </button>
        </div>
    </div>
</article>
