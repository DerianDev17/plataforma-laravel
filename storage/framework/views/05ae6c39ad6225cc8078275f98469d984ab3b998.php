<?php $attributes = $attributes->exceptProps(['type' => 'success', 'dismissible' => true]); ?>
<?php foreach (array_filter((['type' => 'success', 'dismissible' => true]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php
$colors = [
    'success' => 'eus-alert-success',
    'error'   => 'eus-alert-danger',
    'warning' => 'eus-alert-warning',
    'info'    => 'eus-alert-info',
];
$bg = $colors[$type] ?? $colors['success'];
?>

<?php if(session()->has('message')): ?>
<div id="alert" class="eus-alert <?php echo e($bg); ?>" role="alert">
    <span><?php echo e(session('message')); ?></span>
    <?php if($dismissible): ?>
    <button type="button" class="eus-btn eus-btn-ghost eus-btn-sm" onclick="this.parentElement.remove()" aria-label="Cerrar alerta">
        <span aria-hidden="true">&times;</span>
    </button>
    <?php endif; ?>
</div>
<?php endif; ?>
<?php /**PATH C:\Users\t'chala\proyectos\plataforma-laravel\resources\views/components/ui/alert.blade.php ENDPATH**/ ?>