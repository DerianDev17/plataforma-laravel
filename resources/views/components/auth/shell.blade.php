@props(['compact' => false])

<div {{ $attributes->class(['auth-shell']) }}>
    <div @class(['auth-wrap', 'auth-wrap-compact' => $compact])>
        {{ $slot }}
    </div>
</div>
