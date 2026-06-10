<div class="pt-24 pb-16 px-6 max-w-7xl mx-auto w-full">
    <!-- Hero Title -->
    <header class="mb-12 text-center md:text-left">
        <h1 class="text-4xl md:text-5xl font-headline font-bold text-primary mb-4">Packs &amp; Colecciones</h1>
        <p class="text-secondary max-w-2xl text-lg font-body">Descubre el arte de la perfumería a través de nuestras curadurías exclusivas. El regalo perfecto o la oportunidad ideal para encontrar tu esencia característica.</p>
    </header>

    <!-- Collections Bento Grid -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 mb-20">
        <!-- Discovery Set (Large Card) -->
        <div class="md:col-span-8 group relative overflow-hidden bg-surface-container-low rounded-xl card-shadow flex flex-col md:flex-row h-full border border-outline-variant/10">
            <div class="md:w-1/2 overflow-hidden h-64 md:h-full">
                <img alt="Discovery Set" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBg6nOj4vgcjb-4YHKuxXEgutPmNd-RjxMBgM5VuEBNPUJSnfyLXTWvAWDzoYA0DW54_gjhX9hKAbcolUwE-FbsTzcIpO7lMcjwNyJWzcf0FifMgII43S0LuABrgkKY_NoINangWkCGEfQXvEkIsq-EKzlsGSKBv4DORcaLT02UGfh-68_ZD6Vk_s2g5iu5mcFbgjD2uuD7UPnam2HJ5GoLRW5rHsr4H4yRVFHh6pxvAZGUmguuQ7TgV2hxPJ6T4uQ3S5pmTO8LpQk"/>
            </div>
            <div class="md:w-1/2 p-8 flex flex-col justify-center">
                <div class="flex items-center gap-2 text-tertiary mb-3">
                    <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">eco</span>
                    <span class="text-xs font-bold uppercase tracking-widest">Lo más buscado</span>
                </div>
                <h2 class="text-3xl font-headline font-bold text-on-surface mb-4">Discovery Set</h2>
                <p class="text-on-surface-variant font-body mb-6">Explora tres de nuestras fragancias más icónicas en formato de viaje. La introducción perfecta al universo Esencia.</p>
                <ul class="space-y-2 mb-8 text-secondary font-body text-sm">
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-lg">check_circle</span> 3 viales de 2ml cada uno</li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-lg">check_circle</span> Guía de notas olfativas</li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-lg">check_circle</span> Estuche de cartón reciclado premium</li>
                </ul>
                <div class="mt-auto flex items-center justify-between">
                    <span class="text-2xl font-headline font-bold text-primary">{{ cache('store_currency', 'ARS') === 'EUR' ? '€' : '$' }}450 {{ cache('store_currency', 'ARS') !== 'EUR' ? cache('store_currency', 'ARS') : '' }}</span>
                    <button wire:click="addToCart('discovery')" class="bg-primary text-on-primary px-6 py-3 rounded-lg font-bold flex items-center gap-2 active:scale-95 transition-all hover:bg-opacity-90">
                        <span class="material-symbols-outlined text-sm">shopping_bag</span>
                        Añadir al Carrito
                    </button>
                </div>
            </div>
        </div>

        <!-- Gift Box (Square Card) -->
        <div class="md:col-span-4 group relative overflow-hidden bg-surface-container-high rounded-xl card-shadow flex flex-col border border-outline-variant/10">
            <div class="h-64 overflow-hidden">
                <img alt="Gift Box" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBIS1hkh29GdCn5Rk9AZQcfLBUVRPe7c6F3xW8knpZTWT-zgTZ14cG6Rhz-OOe8pv1ki6Sv9iH2s5ouCo7F1wrmGaxN960gliVGyh6uw45Lr8vRTvLNmoMlXhPi3Q74f9riAio-7VfwDhZ0P9JvNw1wqxpyewhRvlmtr5kLIxcVZXXeYISzIe9u_T333nVCtcoMoOYyfy5Gm6uCyLjPXVsSZ7DLE1vWqdGa57z6OdS4F124d2W1IiphN-8L7csQKDA5jTswUl-VZpg"/>
            </div>
            <div class="p-6 flex flex-col flex-grow">
                <h2 class="text-2xl font-headline font-bold text-on-surface mb-2">Terra Scent Gift Box</h2>
                <p class="text-on-surface-variant font-body text-sm mb-6">El regalo definitivo. Una experiencia completa que combina lujo y naturaleza en un empaque artesanal.</p>
                <div class="mt-auto">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xl font-headline font-bold text-primary">{{ cache('store_currency', 'ARS') === 'EUR' ? '€' : '$' }}2,400 {{ cache('store_currency', 'ARS') !== 'EUR' ? cache('store_currency', 'ARS') : '' }}</span>
                        <span class="text-xs bg-tertiary-container/30 text-on-tertiary-container px-2 py-1 rounded">Edición Limitada</span>
                    </div>
                    <button wire:click="addToCart('giftbox')" class="w-full bg-secondary text-on-secondary px-6 py-3 rounded-lg font-bold flex items-center justify-center gap-2 active:scale-95 transition-all hover:bg-on-surface">
                        <span class="material-symbols-outlined text-sm">card_giftcard</span>
                        Añadir al Carrito
                    </button>
                </div>
            </div>
        </div>

        <!-- Exclusive Collection (Small Wide Card) -->
        <div class="md:col-span-12 group relative overflow-hidden bg-surface-container-lowest rounded-xl card-shadow flex flex-col md:flex-row border border-outline-variant/10 h-auto md:h-80">
            <div class="md:w-2/5 p-8 flex flex-col justify-center order-2 md:order-1">
                <h2 class="text-3xl font-headline font-bold text-on-surface mb-4">Exclusive Oud Collection</h2>
                <p class="text-on-surface-variant font-body mb-6">Una selección de fragancias amaderadas profundas y especias exóticas para los gustos más exigentes y sofisticados.</p>
                <div class="flex items-center gap-4">
                    <span class="text-2xl font-headline font-bold text-primary">{{ cache('store_currency', 'ARS') === 'EUR' ? '€' : '$' }}5,200 {{ cache('store_currency', 'ARS') !== 'EUR' ? cache('store_currency', 'ARS') : '' }}</span>
                    <button wire:click="addToCart('exclusive')" class="bg-primary text-on-primary px-8 py-3 rounded-lg font-bold flex items-center gap-2 active:scale-95 transition-all hover:bg-opacity-90">
                        <span class="material-symbols-outlined text-sm">auto_awesome</span>
                        Lo Quiero
                    </button>
                </div>
            </div>
            <div class="md:w-3/5 overflow-hidden order-1 md:order-2">
                <img alt="Exclusive Collection" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDyr1k1jb8o8_2-Q-6FQWm9aPwUH0DaPx835Fni867mIBE_EBFQHyUFkW-9JHKTyOTWuKZlTcc9w9_RtxOWPKoIXV7_UY0nJQFkbuSFeqDKTcpAN8GbgsqqIPaIKZiMXM_wBACR8SbKg4Ud8OKAOt1dlCGA370vyvMrtK4cAbEnYAnhKI5IUxxO4VyuBpeKFPkZ4m4iHyxQba-F_u08gDlwiY-U6Pv1SQnbK6taW_JMWp9u9dECcUEaxRouIxdzQmoZkPmK95J8rQE"/>
            </div>
        </div>
    </div>

    <!-- Custom Set Section -->
    <section class="mt-20 relative overflow-hidden rounded-2xl bg-secondary-container p-8 md:p-16 border border-tertiary-container/20">
        <div class="relative z-10 grid md:grid-cols-2 gap-12 items-center">
            <div>
                <span class="text-tertiary font-bold tracking-[0.2em] uppercase text-xs mb-4 block">Artesanía Olfativa</span>
                <h2 class="text-4xl md:text-5xl font-headline font-bold text-on-secondary-container mb-6">Crea tu propio set personalizado</h2>
                <p class="text-on-secondary-container/80 text-lg font-body mb-8">Elige 5 fragancias que definan tu estilo y nosotros las prepararemos en un estuche exclusivo hecho a mano con materiales sustentables.</p>
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
