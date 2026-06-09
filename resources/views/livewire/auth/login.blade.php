<div class="container mx-auto px-4 py-16 max-w-lg min-h-[80vh] flex flex-col justify-center pt-28 flex-grow">
    <!-- Brand / Logo Header -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center bg-secondary-container text-primary rounded-full p-4 mb-4 shadow-sm border border-outline-variant/20">
            <span class="material-symbols-outlined text-4xl" style="font-variation-settings: 'FILL' 1;">spa</span>
        </div>
        <h1 class="text-4xl font-headline font-bold text-primary mb-2">Esencia</h1>
        <p class="text-on-surface-variant font-body">Tu portal de bienestar y alta perfumería</p>
    </div>

    <!-- Active Alerts Area -->
    <div class="space-y-4 mb-6">
        <!-- 1. Checkout Warning (from session checkout_warning) -->
        @if (session()->has('checkout_warning'))
            <div class="p-4 bg-secondary-container text-on-secondary-container rounded-xl border border-outline-variant/40 flex items-start gap-3 shadow-sm animate-pulse-subtle">
                <span class="material-symbols-outlined text-primary mt-0.5" style="font-variation-settings: 'FILL' 1;">lock</span>
                <div>
                    <h4 class="font-bold text-on-surface text-sm mb-1">Acceso Requerido</h4>
                    <p class="text-xs text-on-secondary-container/90 leading-relaxed">{{ session('checkout_warning') }}</p>
                </div>
            </div>
        @endif

        <!-- 2. General Auth Success (from session auth_success) -->
        @if (session()->has('auth_success'))
            <div class="p-4 bg-on-primary-container text-on-primary-fixed-variant rounded-xl border border-primary/20 flex items-start gap-3 shadow-sm">
                <span class="material-symbols-outlined text-on-primary-fixed-variant mt-0.5" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                <div>
                    <h4 class="font-bold text-on-primary-fixed-variant text-sm mb-1">¡Operación Exitosa!</h4>
                    <p class="text-xs leading-relaxed">{{ session('auth_success') }}</p>
                </div>
            </div>
        @endif

        <!-- 3. General Auth Error (from session auth_error) -->
        @if (session()->has('auth_error'))
            <div class="p-4 bg-error-container text-on-error-container rounded-xl border border-error/20 flex items-start gap-3 shadow-sm">
                <span class="material-symbols-outlined text-error mt-0.5">warning</span>
                <div>
                    <h4 class="font-bold text-on-error-container text-sm mb-1">Error de Acceso</h4>
                    <p class="text-xs leading-relaxed">{{ session('auth_error') }}</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Main Card -->
    <div class="bg-surface rounded-2xl p-8 shadow-[0_10px_40px_rgba(46,50,48,0.08)] border border-outline-variant/30 relative overflow-hidden">
        
        <!-- Toggle Tabs -->
        <div class="flex border-b border-outline-variant/20 mb-8 p-1 bg-surface-container-low rounded-xl">
            <button wire:click="toggleMode" class="flex-1 py-3 text-center rounded-lg font-bold text-sm transition-all duration-300 {{ !$isRegistering ? 'bg-surface text-primary shadow-sm' : 'text-on-surface-variant hover:text-primary' }}">
                Iniciar Sesión
            </button>
            <button wire:click="toggleMode" class="flex-1 py-3 text-center rounded-lg font-bold text-sm transition-all duration-300 {{ $isRegistering ? 'bg-surface text-primary shadow-sm' : 'text-on-surface-variant hover:text-primary' }}">
                Crear Cuenta
            </button>
        </div>

        <!-- Forms -->
        @if (!$isRegistering)
            <!-- Login Form -->
            <form wire:submit.prevent="login" class="space-y-6">
                <div>
                    <label for="email" class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2 font-body">Correo Electrónico</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-3 text-outline text-[20px]">mail</span>
                        <input wire:model="email" type="email" id="email" class="w-full bg-surface border {{ $errors->has('email') ? 'border-error focus:ring-error' : 'border-outline-variant focus:ring-primary' }} rounded-xl pl-10 pr-4 py-2.5 text-on-surface focus:outline-none focus:ring-2 focus:border-transparent transition-all placeholder-outline/50 font-body" placeholder="ejemplo@esencia.com">
                    </div>
                    @error('email')
                        <!-- Inline Validation Error -->
                        <div class="text-error text-xs mt-1.5 flex items-center gap-1 font-body">
                            <span class="material-symbols-outlined text-[14px]">error</span>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2 font-body">Contraseña</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-3 text-outline text-[20px]">lock</span>
                        <input wire:model="password" type="password" id="password" class="w-full bg-surface border {{ $errors->has('password') ? 'border-error focus:ring-error' : 'border-outline-variant focus:ring-primary' }} rounded-xl pl-10 pr-4 py-2.5 text-on-surface focus:outline-none focus:ring-2 focus:border-transparent transition-all placeholder-outline/50 font-body" placeholder="••••••••">
                    </div>
                    @error('password')
                        <!-- Inline Validation Error -->
                        <div class="text-error text-xs mt-1.5 flex items-center gap-1 font-body">
                            <span class="material-symbols-outlined text-[14px]">error</span>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-sm font-body">
                    <label class="flex items-center gap-2 text-on-surface-variant cursor-pointer select-none">
                        <input wire:model="remember" type="checkbox" class="rounded text-primary focus:ring-primary border-outline-variant bg-surface w-4 h-4">
                        <span>Recordarme</span>
                    </label>
                    <a href="#" class="text-primary hover:underline font-semibold">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" wire:loading.attr="disabled" class="w-full bg-primary text-on-primary rounded-xl py-3.5 font-bold text-lg hover:bg-on-primary-fixed-variant transition-all active:scale-[0.98] duration-150 flex justify-center items-center gap-2 shadow-md disabled:opacity-50 font-body">
                    <span wire:loading.remove>Ingresar de forma segura</span>
                    <span wire:loading>Procesando acceso...</span>
                    <span class="material-symbols-outlined" wire:loading.remove>login</span>
                </button>
            </form>
        @else
            <!-- Register Form -->
            <form wire:submit.prevent="register" class="space-y-5">
                <div>
                    <label for="name" class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2 font-body">Nombre Completo</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-3 text-outline text-[20px]">person</span>
                        <input wire:model="name" type="text" id="name" class="w-full bg-surface border {{ $errors->has('name') ? 'border-error focus:ring-error' : 'border-outline-variant focus:ring-primary' }} rounded-xl pl-10 pr-4 py-2.5 text-on-surface focus:outline-none focus:ring-2 focus:border-transparent transition-all placeholder-outline/50 font-body" placeholder="Tu nombre y apellido">
                    </div>
                    @error('name')
                        <!-- Inline Validation Error -->
                        <div class="text-error text-xs mt-1.5 flex items-center gap-1 font-body">
                            <span class="material-symbols-outlined text-[14px]">error</span>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <div>
                    <label for="reg_email" class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2 font-body">Correo Electrónico</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-3 text-outline text-[20px]">mail</span>
                        <input wire:model="email" type="email" id="reg_email" class="w-full bg-surface border {{ $errors->has('email') ? 'border-error focus:ring-error' : 'border-outline-variant focus:ring-primary' }} rounded-xl pl-10 pr-4 py-2.5 text-on-surface focus:outline-none focus:ring-2 focus:border-transparent transition-all placeholder-outline/50 font-body" placeholder="ejemplo@esencia.com">
                    </div>
                    @error('email')
                        <!-- Inline Validation Error -->
                        <div class="text-error text-xs mt-1.5 flex items-center gap-1 font-body">
                            <span class="material-symbols-outlined text-[14px]">error</span>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <div>
                    <label for="reg_password" class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2 font-body">Contraseña</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-3 text-outline text-[20px]">lock</span>
                        <input wire:model="password" type="password" id="reg_password" class="w-full bg-surface border {{ $errors->has('password') ? 'border-error focus:ring-error' : 'border-outline-variant focus:ring-primary' }} rounded-xl pl-10 pr-4 py-2.5 text-on-surface focus:outline-none focus:ring-2 focus:border-transparent transition-all placeholder-outline/50 font-body" placeholder="Mínimo 6 caracteres">
                    </div>
                    @error('password')
                        <!-- Inline Validation Error -->
                        <div class="text-error text-xs mt-1.5 flex items-center gap-1 font-body">
                            <span class="material-symbols-outlined text-[14px]">error</span>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2 font-body">Confirmar Contraseña</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-3 text-outline text-[20px]">lock_reset</span>
                        <input wire:model="password_confirmation" type="password" id="password_confirmation" class="w-full bg-surface border border-outline-variant focus:ring-primary rounded-xl pl-10 pr-4 py-2.5 text-on-surface focus:outline-none focus:ring-2 focus:border-transparent transition-all placeholder-outline/50 font-body" placeholder="Repite tu contraseña">
                    </div>
                </div>

                <button type="submit" wire:loading.attr="disabled" class="w-full bg-primary text-on-primary rounded-xl py-3.5 font-bold text-lg hover:bg-on-primary-fixed-variant transition-all active:scale-[0.98] duration-150 flex justify-center items-center gap-2 shadow-md disabled:opacity-50 mt-2 font-body">
                    <span wire:loading.remove>Registrarse y continuar</span>
                    <span wire:loading>Creando tu cuenta...</span>
                    <span class="material-symbols-outlined" wire:loading.remove>how_to_reg</span>
                </button>
            </form>
        @endif
    </div>

    <!-- 4. Informational Alert (Benefits always visible at the bottom) -->
    <div class="mt-8 p-4 bg-surface-container-low text-on-surface-variant rounded-xl border border-outline-variant/30 flex items-start gap-3 text-sm shadow-[0_2px_15px_rgba(46,50,48,0.03)]">
        <span class="material-symbols-outlined text-primary mt-0.5" style="font-variation-settings: 'FILL' 1;">spa</span>
        <div>
            <h5 class="font-bold text-on-surface text-xs mb-0.5 font-body">Beneficios del Club Esencia</h5>
            <p class="text-xs text-on-surface-variant/90 leading-relaxed font-body">Con tu cuenta acumulas semillas de descuento, guardas tus fragancias favoritas y accedes a preventas exclusivas de decants importados.</p>
        </div>
    </div>
</div>

<style>
    @keyframes pulse-subtle {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.96; transform: scale(0.995); }
    }
    .animate-pulse-subtle {
        animation: pulse-subtle 3s infinite ease-in-out;
    }
</style>
