<?php $attributes = $attributes->exceptProps([
    'variant' => 'info',
    'icon',
    'value',
    'label',
    'progress' => null,
    'note' => null,
]); ?>
<?php foreach (array_filter(([
    'variant' => 'info',
    'icon',
    'value',
    'label',
    'progress' => null,
    'note' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<article class="stat-card stat-card-<?php echo e($variant); ?>">
    <div class="stat-card-icon">
        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.ui.icon','data' => ['name' => $icon]]); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($icon)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
    </div>
    <div class="stat-card-value"><?php echo e($value); ?></div>
    <div class="stat-card-label"><?php echo e($label); ?></div>

    <?php if(! is_null($progress)): ?>
        <div class="stat-progress" style="--value: <?php echo e(max(0, min(100, (int) $progress))); ?>%;">
            <div class="stat-progress-fill"></div>
        </div>
    <?php endif; ?>

    <?php if($note): ?>
        <div class="stat-note"><?php echo e($note); ?></div>
    <?php endif; ?>
</article>
<?php /**PATH C:\Users\t'chala\proyectos\plataforma-laravel\resources\views/components/dashboard/stat-card.blade.php ENDPATH**/ ?>