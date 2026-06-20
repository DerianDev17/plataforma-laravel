<?php if($errors->any()): ?>
    <div <?php echo e($attributes); ?>>
        <div class="validation-errors-title">Por favor, aseg&uacute;rese de llenar correctamente el formulario.</div>

        <ul class="validation-errors-list">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>
<?php /**PATH C:\Users\t'chala\proyectos\plataforma-laravel\resources\views/vendor/jetstream/components/validation-errors.blade.php ENDPATH**/ ?>