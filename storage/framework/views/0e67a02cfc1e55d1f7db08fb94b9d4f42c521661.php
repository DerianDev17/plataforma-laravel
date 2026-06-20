<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="description" content="<?php echo e($description ?? 'Acceso a la plataforma academica Semilla Digital para estudiantes y equipo administrativo.'); ?>">

    <title><?php echo e($title ?? config('app.name', 'Semilla Digital')); ?></title>
    <link rel="icon" type="image/svg+xml" href="<?php echo e(asset('brand/semilla-logo-mark.svg')); ?>">

    <link rel="preload" href="<?php echo e(asset('fonts/manrope-latin.woff2')); ?>" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?php echo e(asset('fonts/fraunces-latin.woff2')); ?>" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="<?php echo e(asset('fonts/semilla-fonts.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(URL::asset('css/modern.css')); ?>" />

    <?php echo $__env->yieldPushContent('estilos'); ?>
</head>

<body class="antialiased guest-bg">
    <a href="#guest-content" class="skip-link">Saltar al contenido principal</a>

    <main id="guest-content">
        <?php echo e($slot); ?>

    </main>

    <?php echo $__env->yieldPushContent('javascripts'); ?>
</body>

</html>
<?php /**PATH C:\Users\t'chala\proyectos\plataforma-laravel\resources\views/layouts/guest.blade.php ENDPATH**/ ?>