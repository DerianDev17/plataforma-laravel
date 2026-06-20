<?php $attributes = $attributes->exceptProps(['name', 'size' => 24]); ?>
<?php foreach (array_filter((['name', 'size' => 24]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php
    $common = [
        'width' => $size,
        'height' => $size,
        'viewBox' => '0 0 24 24',
        'fill' => 'none',
        'stroke' => 'currentColor',
        'stroke-width' => '2',
        'aria-hidden' => 'true',
    ];
?>

<?php switch($name):
    case ('users'): ?>
        <svg <?php echo e($attributes->merge($common)); ?>><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
        <?php break; ?>
    <?php case ('check'): ?>
        <svg <?php echo e($attributes->merge($common)); ?>><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        <?php break; ?>
    <?php case ('calendar'): ?>
        <svg <?php echo e($attributes->merge($common)); ?>><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        <?php break; ?>
    <?php case ('bell'): ?>
        <svg <?php echo e($attributes->merge($common)); ?>><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
        <?php break; ?>
    <?php case ('monitor'): ?>
        <svg <?php echo e($attributes->merge($common)); ?>><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
        <?php break; ?>
    <?php case ('video'): ?>
        <svg <?php echo e($attributes->merge($common)); ?>><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>
        <?php break; ?>
    <?php case ('book'): ?>
        <svg <?php echo e($attributes->merge($common)); ?>><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
        <?php break; ?>
    <?php case ('sprout'): ?>
        <svg <?php echo e($attributes->merge($common)->merge(['stroke-width' => '1.8'])); ?>><path d="M12 21c0-5.25 2.75-8 8-8 0 5.25-2.75 8-8 8Z"/><path d="M12 21c0-6.2-3.4-9.8-9-10 0 5.8 3.2 9.5 9 10Z"/><path d="M12 21V9"/><path d="M12 9c0-3 1.7-5 5-6 .2 3.4-1.5 5.4-5 6Z"/></svg>
        <?php break; ?>
    <?php case ('calculator'): ?>
        <svg <?php echo e($attributes->merge($common)); ?>><rect x="5" y="3" width="14" height="18" rx="2"/><path d="M8 7h8M8 11h2M12 11h2M16 11h.01M8 15h2M12 15h2M16 15h.01"/></svg>
        <?php break; ?>
    <?php default: ?>
        <svg <?php echo e($attributes->merge($common)); ?>><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 2"/></svg>
<?php endswitch; ?>
<?php /**PATH C:\Users\t'chala\proyectos\plataforma-laravel\resources\views/components/ui/icon.blade.php ENDPATH**/ ?>