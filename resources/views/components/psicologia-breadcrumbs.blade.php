@props(['items' => []])

<nav class="flex flex-wrap items-center gap-2 text-xs font-semibold uppercase tracking-[0.25em] text-[#5a7478]">
    @foreach ($items as $item)
        @if (! $loop->first)
            <span class="text-[#adc1c4]">/</span>
        @endif

        @if (! empty($item['url']))
            <a href="{{ $item['url'] }}" class="transition hover:text-[#17353a]">{{ $item['label'] }}</a>
        @else
            <span class="text-[#17353a]">{{ $item['label'] }}</span>
        @endif
    @endforeach
</nav>
