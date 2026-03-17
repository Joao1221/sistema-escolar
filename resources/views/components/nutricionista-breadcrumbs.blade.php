@props(['items' => []])

<nav class="flex flex-wrap items-center gap-2 text-xs font-semibold uppercase tracking-[0.25em] text-[#688073]">
    @foreach ($items as $item)
        @if (! $loop->first)
            <span class="text-[#a6b4ab]">/</span>
        @endif

        @if (! empty($item['url']))
            <a href="{{ $item['url'] }}" class="transition hover:text-[#17332a]">{{ $item['label'] }}</a>
        @else
            <span class="text-[#17332a]">{{ $item['label'] }}</span>
        @endif
    @endforeach
</nav>
