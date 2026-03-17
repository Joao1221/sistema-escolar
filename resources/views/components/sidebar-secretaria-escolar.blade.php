{{-- Mobile Slide-over Menu (Hidden on Desktop) --}}
<div x-show="mobileMenu" 
     class="relative z-[999] lg:hidden" 
     x-cloak
     role="dialog" 
     aria-modal="true">
    
    {{-- Backdrop --}}
    <div x-show="mobileMenu" 
         x-transition:enter="transition-opacity ease-linear duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition-opacity ease-linear duration-300" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm"
         @click="mobileMenu = false"></div>

    <div class="fixed inset-0 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-y-0 left-0 flex max-w-full">
                {{-- Sidebar Content (Mobile/Tablet) --}}
                <div x-show="mobileMenu" 
                     x-transition:enter="transition ease-in-out duration-300 transform" 
                     x-transition:enter-start="-translate-x-full" 
                     x-transition:enter-end="translate-x-0" 
                     x-transition:leave="transition ease-in-out duration-300 transform" 
                     x-transition:leave-start="translate-x-0" 
                     x-transition:leave-end="-translate-x-full" 
                     class="pointer-events-auto w-screen max-w-xs bg-emerald-900 flex flex-col shadow-2xl">
                    
                    <div class="absolute top-0 right-0 -mr-12 pt-4">
                        <button type="button" 
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white transition"
                                @click="mobileMenu = false">
                            <span class="sr-only">Fechar menu</span>
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="h-full flex flex-col min-h-0 bg-emerald-900">
                        @include('components.sidebar-content-secretaria-escolar')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Static Sidebar (Desktop/Huge Tablet) --}}
<nav class="w-64 bg-slate-900 text-white min-h-screen flex flex-col flex-shrink-0 shadow-xl" style="background-color: #064e3b;">
    @include('components.sidebar-content-secretaria-escolar')
</nav>
