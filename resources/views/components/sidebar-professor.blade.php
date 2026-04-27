@php
    $theme = $theme ?? (auth()->user()?->theme ?? 'lilas');
    $pal = match ($theme) {
        'grafite' => [
            'border' => 'rgba(59,130,246,0.35)',
            'bgGradient' => 'radial-gradient(circle at top,#182334 0%,#0f1826 45%,#0b1220 100%)',
            'iconBg' => '#3b82f6',
            'iconText' => '#0b1220',
            'title' => '#e5edff',
            'label' => 'rgba(96, 165, 250, 0.9)',
            'muted' => 'rgba(255,255,255,0.7)',
            'activeBg' => '#3b82f6',
            'activeText' => '#0b1220',
            'inactiveText' => 'rgba(255,255,255,0.9)',
            'divider' => 'rgba(255,255,255,0.1)',
        ],
        'verde' => [
            'border' => 'rgba(52,211,153,0.35)',
            'bgGradient' => 'radial-gradient(circle at top,#134232 0%,#0c2f23 50%,#081f16 100%)',
            'iconBg' => '#10b981',
            'iconText' => '#062019',
            'title' => '#e7fff3',
            'label' => 'rgba(74,222,128,0.9)',
            'muted' => 'rgba(231,255,243,0.72)',
            'activeBg' => '#10b981',
            'activeText' => '#062019',
            'inactiveText' => 'rgba(231,255,243,0.92)',
            'divider' => 'rgba(255,255,255,0.08)',
        ],
        default => [
            'border' => 'rgba(111,74,168,0.4)',
            'bgGradient' => 'radial-gradient(circle at top,#4a2d63 0%,#2c1b43 50%,#1a0f2b 100%)',
            'iconBg' => '#c58bf2',
            'iconText' => '#1f0f2f',
            'title' => '#ffffff',
            'label' => '#c58bf2',
            'muted' => 'rgba(255,255,255,0.72)',
            'activeBg' => '#c58bf2',
            'activeText' => '#1f0f2f',
            'inactiveText' => 'rgba(255,255,255,0.9)',
            'divider' => 'rgba(255,255,255,0.1)',
        ],
    };

    $linkStyle = function (bool $active) use ($pal) {
        return $active
            ? "background: {$pal['activeBg']}; color: {$pal['activeText']}; box-shadow: 0 12px 28px rgba(0,0,0,0.16);"
            : "color: {$pal['inactiveText']};";
    };
@endphp

<aside class="flex h-full w-full flex-col p-3 lg:p-5 text-white">
    <div class="h-full overflow-hidden rounded-3xl border shadow-lg overflow-y-auto"
         style="border-radius:32px;border:1px solid {{ $pal['border'] }};background:{{ $pal['bgGradient'] }};box-shadow:0 18px 50px rgba(17,12,34,0.45);">
        <div class="px-6 pb-6 pt-8" style="border-bottom:1px solid {{ $pal['divider'] }};">
            <div class="flex items-center gap-4">
                <div class="flex h-16 w-16 items-center justify-center rounded-2xl shadow-lg"
                     style="border-radius:22px;background:{{ $pal['iconBg'] }};color:{{ $pal['iconText'] }};box-shadow:0 15px 30px rgba(0,0,0,0.18);">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422A12.083 12.083 0 0118 17.5c0 1.105-2.686 2-6 2s-6-.895-6-2c0-2.332.807-4.477 2.16-6.078L12 14z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase" style="letter-spacing:0.34em;color:{{ $pal['label'] }};">Portal dedicado</p>
                    <h2 class="font-outfit text-2xl font-semibold">Professor</h2>
                    <p class="text-sm" style="color:{{ $pal['muted'] }};">Rotina pedagógica e diário eletrônico</p>
                </div>
            </div>
        </div>

        <div class="px-4 py-3">
            <p class="px-3 text-xs font-bold uppercase" style="letter-spacing:0.3em;color:{{ $pal['label'] }};">Visão Geral</p>
            <div class="mt-1" style="display:flex;flex-direction:column;gap:6px;">
                <a href="{{ route('professor.dashboard') }}"
                   class="flex items-center gap-3 rounded-2xl px-4 transition"
                   style="padding-top:7px;padding-bottom:7px;{{ $linkStyle(request()->routeIs('professor.dashboard')) }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 13h8V3H3v10zm10 8h8V3h-8v18zm-10 0h8v-6H3v6z" />
                    </svg>
                    <span class="font-semibold">Dashboard</span>
                </a>

                <a href="{{ route('professor.turmas.index') }}"
                   class="flex items-center gap-3 rounded-2xl px-4 transition"
                   style="padding-top:7px;padding-bottom:7px;{{ $linkStyle(request()->routeIs('professor.turmas.*')) }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4a2 2 0 012-2m14 0V7a2 2 0 00-2-2H5a2 2 0 00-2 2v4" />
                    </svg>
                    <span class="font-semibold">Minhas turmas</span>
                </a>

                <a href="{{ route('professor.horarios.index') }}"
                   class="flex items-center gap-3 rounded-2xl px-4 transition"
                   style="padding-top:7px;padding-bottom:7px;{{ $linkStyle(request()->routeIs('professor.horarios.*')) }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-semibold">Meu horário</span>
                </a>
            </div>
        </div>

        <div class="px-4 py-3" style="border-top:1px solid {{ $pal['divider'] }};">
            <p class="px-3 text-xs font-bold uppercase" style="letter-spacing:0.3em;color:{{ $pal['label'] }};">Diário Eletrônico</p>
            <div class="mt-1" style="display:flex;flex-direction:column;gap:6px;">
                <a href="{{ route('professor.diario.index') }}"
                   class="flex items-center gap-3 rounded-2xl px-4 transition"
                   style="padding-top:7px;padding-bottom:7px;{{ $linkStyle(request()->routeIs('professor.diario.index')) }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="font-semibold">Meus diários</span>
                </a>

                <a href="{{ route('professor.diario.create') }}"
                   class="flex items-center gap-3 rounded-2xl px-4 transition"
                   style="padding-top:7px;padding-bottom:7px;{{ $linkStyle(request()->routeIs('professor.diario.create')) }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6" />
                    </svg>
                    <span class="font-semibold">Abrir diário</span>
                </a>
            </div>
        </div>

        @can('consultar documentos do professor')
        <div class="px-4 py-3" style="border-top:1px solid {{ $pal['divider'] }};">
            <p class="px-3 text-xs font-bold uppercase" style="letter-spacing:0.3em;color:{{ $pal['label'] }};">Documentos</p>
            <div class="mt-1" style="display:flex;flex-direction:column;gap:6px;">
                <a href="{{ route('professor.documentos.index') }}"
                   class="flex items-center gap-3 rounded-2xl px-4 transition"
                   style="padding-top:7px;padding-bottom:7px;{{ $linkStyle(request()->routeIs('professor.documentos.*')) }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h10m-7 4h7M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" />
                    </svg>
                    <span class="font-semibold">Documentos</span>
                </a>
            </div>
        </div>
        @endcan

        @can('consultar auditoria do proprio trabalho docente')
        <div class="px-4 py-3" style="border-top:1px solid {{ $pal['divider'] }};">
            <p class="px-3 text-xs font-bold uppercase" style="letter-spacing:0.3em;color:{{ $pal['label'] }};">Auditoria</p>
            <div class="mt-1" style="display:flex;flex-direction:column;gap:6px;">
                <a href="{{ route('professor.auditoria.index') }}"
                   class="flex items-center gap-3 rounded-2xl px-4 transition"
                   style="padding-top:7px;padding-bottom:7px;{{ $linkStyle(request()->routeIs('professor.auditoria.*')) }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-semibold">Meus rastros</span>
                </a>
            </div>
        </div>
        @endcan

        <div class="px-6 py-6 space-y-3" style="border-top:1px solid {{ $pal['divider'] }};">
            <form method="POST" action="{{ route('professor.theme.update') }}" class="space-y-2">
                @csrf
                <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
                <p class="text-[11px] font-semibold uppercase tracking-[0.28em]" style="color:{{ $pal['label'] }};">Tema do portal</p>
                <div class="grid grid-cols-3 gap-2">
                    <button type="submit" name="theme" value="lilas"
                            class="w-full rounded-xl px-3 py-2 text-xs font-bold uppercase tracking-widest transition"
                            style="border:1px solid {{ $pal['border'] }}; background: {{ $theme === 'lilas' ? $pal['activeBg'] : 'transparent' }}; color: {{ $theme === 'lilas' ? $pal['activeText'] : $pal['inactiveText'] }};">
                        Lilás
                    </button>
                    <button type="submit" name="theme" value="grafite"
                            class="w-full rounded-xl px-3 py-2 text-xs font-bold uppercase tracking-widest transition"
                            style="border:1px solid {{ $pal['border'] }}; background: {{ $theme === 'grafite' ? $pal['activeBg'] : 'transparent' }}; color: {{ $theme === 'grafite' ? $pal['activeText'] : $pal['inactiveText'] }};">
                        Grafite
                    </button>
                    <button type="submit" name="theme" value="verde"
                            class="w-full rounded-xl px-3 py-2 text-xs font-bold uppercase tracking-widest transition"
                            style="border:1px solid {{ $pal['border'] }}; background: {{ $theme === 'verde' ? $pal['activeBg'] : 'transparent' }}; color: {{ $theme === 'verde' ? $pal['activeText'] : $pal['inactiveText'] }};">
                        Verde
                    </button>
                </div>
            </form>
        </div>
    </div>
</aside>
