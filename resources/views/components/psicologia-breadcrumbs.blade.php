@props(['items' => []])

<nav class="flex flex-wrap items-center gap-2 text-xs font-semibold uppercase tracking-[0.25em] text-white/80">
    @foreach ($items as $item)
        @if (! $loop->first)
            <span class="text-white/50">/</span>
        @endif

        @if (! empty($item['url']))
            <a href="{{ $item['url'] }}" class="transition hover:font-bold" style="color: #ffffff !important;">{{ $item['label'] }}</a>
        @else
            <span class="text-white">{{ $item['label'] }}</span>
        @endif
    @endforeach
</nav>
