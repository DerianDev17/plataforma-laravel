<?php $attributes = $attributes->exceptProps([
    'eyebrow' => null,
    'title',
    'copy' => null,
    'center' => false,
    'mobileLogo' => false,
]); ?>
<?php foreach (array_filter(([
    'eyebrow' => null,
    'title',
    'copy' => null,
    'center' => false,
    'mobileLogo' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div class="<?php echo \Illuminate\Support\Arr::toCssClasses(['auth-panel-heading', 'auth-panel-heading-center' => $center]) ?>">
    <?php if($mobileLogo): ?>
        <div class="auth-mobile-logo">
            <a href="/" class="auth-mobile-brand-link" aria-label="Ir al inicio">
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.brand.logo','data' => ['alt' => '','class' => 'brand-logo-mobile-mark']]); ?>
<?php $component->withName('brand.logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['alt' => '','class' => 'brand-logo-mobile-mark']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                <span class="auth-mobile-brand-name">Semilla Digital</span>
            </a>
        </div>
    <?php endif; ?>

    <?php if($eyebrow): ?>
        <p class="dashboard-eyebrow text-brand"><?php echo $eyebrow; ?></p>
    <?php endif; ?>

    <h1 class="auth-panel-title"><?php echo $title; ?></h1>

    <?php if($copy): ?>
        <p class="auth-panel-copy"><?php echo $copy; ?></p>
    <?php endif; ?>
</div>
<?php /**PATH C:\Users\t'chala\proyectos\plataforma-laravel\resources\views/components/auth/panel-header.blade.php ENDPATH**/ ?>