@props(['items' => []])

@if (! empty($items))
    <nav class="flex flex-wrap items-center gap-2 text-xs uppercase tracking-[0.22em] text-[#8b6f5a]">
        @foreach ($items as $item)
            @if (! $loop->first)
                <span class="text-[#b89a80]">/</span>
            @endif

            @if (! empty($item['url']))
                <a href="{{ $item['url'] }}" class="font-semibold hover:text-[#7b4b2a] transition">
                    {{ $item['label'] }}
                </a>
            @else
                <span class="font-semibold text-[#5c4337]">{{ $item['label'] }}</span>
            @endif
        @endforeach
    </nav>
@endif
