@props([
    'eyebrow' => null,
    'title',
    'copy' => null,
    'center' => false,
    'mobileLogo' => false,
])

<div @class(['auth-panel-heading', 'auth-panel-heading-center' => $center])>
    @if($mobileLogo)
        <div class="auth-mobile-logo">
            <a href="/" class="auth-mobile-brand-link" aria-label="Ir al inicio">
                <x-brand.logo alt="" class="brand-logo-mobile-mark" />
                <span class="auth-mobile-brand-name">Semilla Digital</span>
            </a>
        </div>
    @endif

    @if($eyebrow)
        <p class="dashboard-eyebrow text-brand">{!! $eyebrow !!}</p>
    @endif

    <h1 class="auth-panel-title">{!! $title !!}</h1>

    @if($copy)
        <p class="auth-panel-copy">{!! $copy !!}</p>
    @endif
</div>
