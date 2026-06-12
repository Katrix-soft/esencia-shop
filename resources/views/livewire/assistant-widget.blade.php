<div class="fixed bottom-6 right-6 z-50 flex flex-col items-end" x-data="{ isOpen: false }">
    <!-- Chat Panel -->
    <div 
        x-show="isOpen"
        x-transition:enter="transition ease-out duration-300 transform origin-bottom-right"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-300 transform origin-bottom-right"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        style="display: none;"
        class="mb-4 w-[340px] bg-surface-container-lowest rounded-2xl shadow-[0_8px_30px_rgba(46,50,48,0.12)] border border-surface-container-highest overflow-hidden flex flex-col"
    >
        <!-- Header -->
        <div class="bg-primary text-on-primary p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-on-primary/20 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">auto_awesome</span>
                </div>
                <div>
                    <h4 class="font-headline font-bold text-sm leading-tight">Asistente Olfativo IA</h4>
                    <p class="text-xs text-on-primary/80 font-body">En línea</p>
                </div>
            </div>
            <button @click="isOpen = false" class="text-on-primary hover:bg-on-primary/10 rounded-full w-8 h-8 flex items-center justify-center transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        
        <!-- Messages Area -->
        <div class="p-4 h-64 overflow-y-auto bg-surface-bright flex flex-col gap-4" id="chat-messages-container" x-ref="messagesContainer" x-init="$watch('messages', () => { $refs.messagesContainer.scrollTop = $refs.messagesContainer.scrollHeight })">
            @foreach($messages as $msg)
                @if($msg['role'] === 'user')
                    <div class="flex gap-2 flex-row-reverse">
                        <div class="bg-primary-container text-on-primary-container p-3 rounded-2xl rounded-tr-sm text-sm max-w-[85%] leading-relaxed shadow-sm">
                            {{ $msg['content'] }}
                        </div>
                    </div>
                @else
                    <div class="flex gap-2">
                        <div class="w-6 h-6 rounded-full bg-primary/10 flex-shrink-0 flex items-center justify-center mt-1">
                            <span class="material-symbols-outlined text-[14px] text-primary">auto_awesome</span>
                        </div>
                        <div class="bg-surface-container p-3 rounded-2xl rounded-tl-sm text-sm text-on-surface max-w-[85%] leading-relaxed shadow-sm">
                            {!! nl2br(e($msg['content'])) !!}
                        </div>
                    </div>
                @endif
            @endforeach

            <!-- Typing indicator -->
            <div wire:loading wire:target="sendMessage" class="flex gap-2">
                <div class="w-6 h-6 rounded-full bg-primary/10 flex-shrink-0 flex items-center justify-center mt-1">
                    <span class="material-symbols-outlined text-[14px] text-primary">auto_awesome</span>
                </div>
                <div class="bg-surface-container p-3 rounded-2xl rounded-tl-sm text-sm text-on-surface max-w-[85%] leading-relaxed shadow-sm flex items-center gap-1 h-8">
                    <span class="w-1.5 h-1.5 bg-primary/50 rounded-full animate-bounce"></span>
                    <span class="w-1.5 h-1.5 bg-primary/50 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                    <span class="w-1.5 h-1.5 bg-primary/50 rounded-full animate-bounce" style="animation-delay: 0.4s"></span>
                </div>
            </div>
        </div>
        
        <!-- Input Area -->
        <form wire:submit.prevent="sendMessage" class="p-3 bg-surface-container-lowest border-t border-surface-container-highest flex items-center gap-2">
            <input type="text" wire:model="userInput" wire:loading.attr="disabled" class="flex-grow bg-surface border-none rounded-full px-4 py-2 text-sm text-on-background focus:ring-2 focus:ring-primary/50 placeholder-on-surface-variant/60 disabled:opacity-50" placeholder="Escribe tu preferencia...">
            <button type="submit" wire:loading.attr="disabled" class="w-10 h-10 rounded-full bg-primary text-on-primary flex items-center justify-center hover:bg-primary/90 transition-colors flex-shrink-0 shadow-sm disabled:opacity-50">
                <span class="material-symbols-outlined text-[20px]" wire:loading.remove wire:target="sendMessage">send</span>
                <span class="material-symbols-outlined text-[20px] animate-spin" wire:loading wire:target="sendMessage" style="display:none;">sync</span>
            </button>
            <button type="button" wire:click="resetChat" title="Reiniciar chat" class="w-10 h-10 rounded-full bg-surface-container-high text-on-surface flex items-center justify-center hover:bg-surface-container-highest transition-colors flex-shrink-0 shadow-sm">
                <span class="material-symbols-outlined text-[20px]">delete</span>
            </button>
        </form>
    </div>
    
    <!-- FAB -->
    <button @click="isOpen = !isOpen" class="w-16 h-16 bg-primary text-on-primary rounded-2xl shadow-[0_4px_20px_rgba(74,124,89,0.3)] flex items-center justify-center hover:-translate-y-1 hover:shadow-[0_6px_25px_rgba(74,124,89,0.4)] transition-all duration-300 group">
        <span class="material-symbols-outlined text-3xl group-hover:scale-110 transition-transform" style="font-variation-settings: 'FILL' 1;">forum</span>
        <div class="absolute -top-2 -right-2 w-4 h-4 bg-tertiary rounded-full border-2 border-surface-container-lowest"></div>
    </button>
</div>
