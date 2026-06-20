<?php $attributes = $attributes->exceptProps(['compact' => false]); ?>
<?php foreach (array_filter((['compact' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div <?php echo e($attributes->class(['auth-shell'])); ?>>
    <div class="<?php echo \Illuminate\Support\Arr::toCssClasses(['auth-wrap', 'auth-wrap-compact' => $compact]) ?>">
        <?php echo e($slot); ?>

    </div>
</div>
<?php /**PATH C:\Users\t'chala\proyectos\plataforma-laravel\resources\views/components/auth/shell.blade.php ENDPATH**/ ?>