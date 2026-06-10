<nav class="sticky top-0 z-50 bg-surface shadow-sm font-body leading-relaxed text-label-sm">
    <div class="flex justify-between items-center w-full px-6 py-4 max-w-full mx-auto">
        <!-- Brand -->
        <a class="text-2xl font-headline font-bold text-primary flex items-center gap-2" href="{{ route('catalog') }}" wire:navigate>
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">spa</span>
            Esencia
        </a>

        <!-- Navigation Links (Desktop) -->
        <ul class="hidden md:flex items-center gap-8 h-full">
            <li>
                <a class="flex items-center h-full py-2 px-3 active:scale-95 duration-150 {{ request()->routeIs('catalog') ? 'text-primary font-bold border-b-2 border-primary' : 'text-on-surface-variant hover:text-primary hover:bg-secondary-container transition-colors rounded' }}" href="{{ route('catalog') }}" wire:navigate>
                    Catálogo
                </a>
            </li>
            <li>
                <a class="flex items-center h-full py-2 px-3 active:scale-95 duration-150 {{ request()->routeIs('packs') ? 'text-primary font-bold border-b-2 border-primary' : 'text-on-surface-variant hover:text-primary hover:bg-secondary-container transition-colors rounded' }}" href="{{ route('packs') }}" wire:navigate>
                    Packs
                </a>
            </li>
            <li>
                <a class="flex items-center h-full py-2 px-3 active:scale-95 duration-150 {{ request()->routeIs('perfil-olfativo') ? 'text-primary font-bold border-b-2 border-primary' : 'text-on-surface-variant hover:text-primary hover:bg-secondary-container transition-colors rounded' }}" href="{{ route('perfil-olfativo') }}" wire:navigate>
                    Mi Perfil Olfativo
                </a>
            </li>
        </ul>


        <!-- Trailing Actions -->
        <div class="flex items-center gap-4 text-primary">
            <!-- Barra de Búsqueda Visible -->
            <div class="hidden md:flex items-center bg-surface-container-low rounded-full px-4 py-1.5 border border-outline-variant/30 focus-within:border-primary/50 focus-within:ring-1 focus-within:ring-primary/50 transition-all group">
                <span class="material-symbols-outlined text-on-surface-variant text-[20px] group-focus-within:text-primary transition-colors">search</span>
                <input wire:model="searchQuery" wire:keydown.enter="performSearch" type="text" placeholder="Buscar..." class="bg-transparent border-none focus:ring-0 text-sm w-32 md:w-40 lg:w-56 transition-all duration-300 text-on-surface placeholder-on-surface-variant/70 px-2 outline-none">
            </div>
            @auth
                <!-- Dropdown de Usuario -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-1.5 px-3 py-1.5 rounded-full hover:bg-secondary-container transition-colors active:scale-95 duration-150 text-primary">
                        <span class="material-symbols-outlined text-[22px]">account_circle</span>
                        <span class="text-sm font-bold max-w-[120px] truncate font-body">{{ auth()->user()->name }}</span>
                        <span class="material-symbols-outlined text-[16px] transition-transform" :class="open ? 'rotate-180' : ''">expand_more</span>
                    </button>
                    <!-- Menú Desplegable -->
                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-surface border border-outline-variant/30 rounded-xl shadow-lg py-2 z-50" style="display: none;">
                        <div class="px-4 py-2 border-b border-outline-variant/20 text-[10px] text-on-surface-variant font-bold uppercase tracking-wider font-body">
                            Mi Cuenta
                        </div>
                        @if(auth()->user() && (str_contains(auth()->user()->email, 'admin') || (isset(auth()->user()->is_admin) && auth()->user()->is_admin)))
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-primary hover:bg-secondary-container transition-colors font-bold font-body" wire:navigate>
                                <span class="material-symbols-outlined text-sm">admin_panel_settings</span>
                                Administración
                            </a>
                        @endif
                        <a href="{{ route('client.portal') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-on-surface hover:bg-secondary-container transition-colors font-body" wire:navigate>
                            <span class="material-symbols-outlined text-sm">dashboard</span>
                            Mi Panel de Cliente
                        </a>
                        <a href="{{ route('perfil-olfativo') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-on-surface hover:bg-secondary-container transition-colors font-body" wire:navigate>
                            <span class="material-symbols-outlined text-sm">spa</span>
                            Perfil Olfativo
                        </a>
                        <button wire:click="logout" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-error hover:bg-error-container/20 transition-colors text-left font-bold font-body">
                            <span class="material-symbols-outlined text-sm">logout</span>
                            Cerrar Sesión
                        </button>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-secondary-container transition-colors active:scale-95 duration-150" wire:navigate>
                    <span class="material-symbols-outlined" data-icon="person">person</span>
                </a>
            @endauth
            <a href="{{ route('cart') }}" wire:navigate class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-secondary-container transition-colors active:scale-95 duration-150 relative {{ request()->routeIs('cart') ? 'text-primary font-bold bg-secondary-container' : '' }}">
                <span class="material-symbols-outlined" data-icon="shopping_cart">shopping_cart</span>
                @if($this->cartCount > 0)
                    <span class="absolute -top-1 -right-1 bg-error text-white text-[10px] font-bold rounded-full w-5 h-5 flex items-center justify-center border border-surface">
                        {{ $this->cartCount }}
                    </span>
                @endif
            </a>
            <!-- Mobile Menu Toggle -->
            <button class="md:hidden flex items-center justify-center w-10 h-10 rounded-full hover:bg-secondary-container text-on-surface-variant">
                <span class="material-symbols-outlined">menu</span>
            </button>
        </div>
    </div>
</nav>
