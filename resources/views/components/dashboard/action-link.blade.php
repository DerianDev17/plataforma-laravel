@props(['href', 'icon'])

<a href="{{ $href }}" class="eus-btn eus-btn-secondary action-button">
    <x-ui.icon :name="$icon" :size="18" />
    {{ $slot }}
</a>
