@props([
    'href' => '#',
    'variant' => 'terra', // terra, primary, outline
    'icon' => null,
])

<a href="{{ $href }}" class="btn btn-{{ $variant }}">
    @if($icon)
        {!! $icon !!}
    @endif
    {{ $slot }}
</a>
