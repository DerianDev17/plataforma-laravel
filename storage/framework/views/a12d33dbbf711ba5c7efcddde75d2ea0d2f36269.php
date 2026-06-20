<?php $attributes = $attributes->exceptProps([
    'variant' => 'mark',
    'alt' => 'Semilla Digital',
]); ?>
<?php foreach (array_filter(([
    'variant' => 'mark',
    'alt' => 'Semilla Digital',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php
    $src = $variant === 'horizontal'
        ? asset('brand/semilla-logo-horizontal.svg')
        : asset('brand/semilla-logo-mark.svg');
?>

<img <?php echo e($attributes->merge(['src' => $src, 'alt' => $alt])); ?>>
<?php /**PATH C:\Users\t'chala\proyectos\plataforma-laravel\resources\views/components/brand/logo.blade.php ENDPATH**/ ?>