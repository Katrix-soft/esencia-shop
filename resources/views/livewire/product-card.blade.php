<article class="bg-surface-container-lowest rounded-2xl overflow-hidden shadow-[0_4px_20px_rgba(46,50,48,0.04)] border border-surface-container flex flex-col group transition-transform duration-300 hover:-translate-y-1">
    <div class="aspect-[4/5] w-full bg-surface-container-low relative overflow-hidden">
        <img alt="{{ $product->name ?? 'Producto' }}" class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500" src="{{ $product->image ?? '' }}" />
        
        @if(!empty($product->badge))
        <div class="absolute top-4 right-4 bg-surface-container-lowest/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-tertiary shadow-sm">
            {{ $product->badge }}
        </div>
        @endif

        @php
            $finalPrice = $product->discounted_price ?? $product->price ?? 0;
            $puntos = app(\App\Services\ClubPointsService::class)->calculatePointsForProduct($product);
            $rating = $product->rating;
        @endphp

        <!-- Club Points Badge overlay -->
        @if(cache('club_cologne_enabled', true) && cache('plan_id', '') !== 'toilette')
        <div class="absolute bottom-4 left-4">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/95 backdrop-blur-md text-[#2f5a43] rounded-full text-[11px] font-bold shadow-sm border border-[#e8dfce]/50">
                <span class="material-symbols-outlined text-[14px] text-[#4a7c59]">loyalty</span>
                + {{ $puntos }} Club Cologne
            </span>
        </div>
        @endif
    </div>
    <div class="p-5 flex flex-col flex-grow">
        <!-- Rating Stars -->
        @if($rating !== null && $rating > 0)
        <div class="flex items-center gap-1 mb-2">
            <div class="flex text-[#dcc48e]">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= floor($rating))
                        <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
                    @elseif($i - 0.5 <= $rating)
                        <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star_half</span>
                    @else
                        <span class="material-symbols-outlined text-[16px]">star</span>
                    @endif
                @endfor
            </div>
            <span class="text-xs text-[#8a6e30] font-bold ml-1">{{ number_format($rating, 1) }}</span>
        </div>
        @endif

        @if(!empty($product->category) || !empty($product->brand))
        <div class="flex flex-wrap gap-2 mb-2">
            @if(!empty($product->brand))
            <span class="text-[10px] uppercase tracking-wider text-outline-variant font-bold">{{ $product->brand }}</span>
            @endif
        </div>
        @endif

        <h3 class="font-headline text-lg font-bold text-on-background mb-1">{{ $product->name ?? 'Producto sin nombre' }}</h3>
        
        @if(!empty($product->category))
            <span class="text-xs text-on-surface-variant font-body mb-3 block">{{ $product->category->name }}</span>
        @endif

        <!-- Fragella Headless Widget -->
        <div class="fragella-inject-accords mb-4" data-fragrance-name="{{ $product->name }}"></div>
        
        @php
            $currencyCode = cache('store_currency', 'ARS');
            $currencySymbol = $currencyCode === 'EUR' ? '€' : '$';
        @endphp
        
        <div class="mt-auto pt-4 border-t border-surface-container-highest flex flex-col gap-3">
            <div class="flex items-center justify-between">
                @if($product->discount > 0)
                    <div class="flex flex-col">
                        <span class="text-[11px] font-bold text-outline-variant line-through mb-0.5">{{ $currencySymbol }}{{ number_format($product->price ?? 0, 0, ',', '.') }}</span>
                        <span class="font-headline font-bold text-lg text-primary flex items-center gap-1.5">
                            {{ $currencySymbol }}{{ number_format($finalPrice, 0, ',', '.') }}
                            <span class="bg-[#dcc48e]/20 text-[#8a6e30] px-1 py-0.5 rounded text-[9px] font-bold uppercase">{{ $product->discount }}% OFF</span>
                        </span>
                    </div>
                @else
                    <span class="font-headline font-bold text-lg text-primary">{{ $currencySymbol }}{{ number_format($product->price ?? 0, 0, ',', '.') }}</span>
                @endif
                
                <button wire:click="addToCart" class="w-10 h-10 flex items-center justify-center bg-primary text-on-primary hover:bg-primary/90 rounded-full transition-colors shadow-sm group-hover:scale-110">
                    <span class="material-symbols-outlined text-[20px]">add_shopping_cart</span>
                </button>
            </div>
        </div>
    </div>
</article>
