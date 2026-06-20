@props([
    'variant' => 'info',
    'icon',
    'value',
    'label',
    'progress' => null,
    'note' => null,
])

<article class="stat-card stat-card-{{ $variant }}">
    <div class="stat-card-icon">
        <x-ui.icon :name="$icon" />
    </div>
    <div class="stat-card-value">{{ $value }}</div>
    <div class="stat-card-label">{{ $label }}</div>

    @if(! is_null($progress))
        <div class="stat-progress" style="--value: {{ max(0, min(100, (int) $progress)) }}%;">
            <div class="stat-progress-fill"></div>
        </div>
    @endif

    @if($note)
        <div class="stat-note">{{ $note }}</div>
    @endif
</article>
