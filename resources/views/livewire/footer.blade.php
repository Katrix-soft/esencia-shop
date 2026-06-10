<footer class="w-full relative bottom-0 bg-surface-container-highest dark:bg-on-background font-body text-sm text-on-surface-variant flat no shadows">
    <div class="w-full px-8 py-12 flex flex-col items-center gap-4 text-center">
        <!-- Brand Logo -->
        <div class="font-headline text-lg text-primary mb-2">
            Esencia
        </div>
        
        <!-- Links -->
        <ul class="flex flex-wrap justify-center gap-6">
            <li>
                <a class="text-secondary hover:text-primary hover:underline decoration-primary opacity-90 hover:opacity-100 transition-all flex items-center gap-1" href="https://wa.me/{{ str_replace(['+', ' '], '', cache('store_whatsapp', '54911223344')) }}" target="_blank">
                    <span class="material-symbols-outlined text-sm">chat</span> WhatsApp Soporte
                </a>
            </li>
            <li>
                <a class="text-secondary hover:text-primary hover:underline decoration-primary opacity-90 hover:opacity-100 transition-all flex items-center gap-1" href="https://instagram.com/{{ str_replace('@', '', cache('store_instagram', 'esencia.latam')) }}" target="_blank">
                    <span class="material-symbols-outlined text-sm">photo_camera</span> Instagram
                </a>
            </li>
            <li>
                <a class="text-secondary hover:text-primary hover:underline decoration-primary opacity-90 hover:opacity-100 transition-all flex items-center gap-1" href="mailto:{{ cache('store_email', 'soporte@esencia.com') }}">
                    <span class="material-symbols-outlined text-sm">mail</span> {{ cache('store_email', 'soporte@esencia.com') }}
                </a>
            </li>
            <li>
                <a class="text-secondary hover:text-primary hover:underline decoration-primary opacity-90 hover:opacity-100 transition-all" href="#">Guía Olfativa</a>
            </li>
        </ul>
        
        <!-- Copyright -->
        <div class="mt-4 text-xs">
            © {{ date('Y') }} {{ cache('store_name', 'Esencia') }} - Perfumería Inteligente LATAM
        </div>
    </div>
</footer>
