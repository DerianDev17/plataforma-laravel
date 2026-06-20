<section class="auth-visual" aria-label="Semilla Digital">
    <img class="auth-visual-image" src="<?php echo e(Storage::url('img/slider2-1200x700.jpg')); ?>" alt="Estudiantes de Semilla Digital en clase">

    <a href="/" class="auth-brand-link" aria-label="Ir al inicio de Semilla Digital">
        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.brand.logo','data' => ['alt' => '','class' => 'auth-brand-mark']]); ?>
<?php $component->withName('brand.logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['alt' => '','class' => 'auth-brand-mark']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
        <span class="auth-brand-copy" aria-hidden="true">
            <span class="auth-brand-name">Semilla</span>
            <span class="auth-brand-name auth-brand-name-accent">Digital</span>
        </span>
    </a>

    <div>
        <p class="auth-eyebrow">Plataforma acad&eacute;mica</p>
        <h1 class="auth-title">Aula virtual para avanzar con orden.</h1>
        <p class="auth-copy">
            Revisa clases, recursos y seguimiento desde un entorno preparado para estudiantes y equipo acad&eacute;mico.
        </p>

        <div class="auth-stat-grid" aria-label="Beneficios de la plataforma">
            <div class="auth-stat">
                <strong>24/7</strong>
                <span>Recursos disponibles</span>
            </div>
            <div class="auth-stat">
                <strong>1 lugar</strong>
                <span>Clases y materiales</span>
            </div>
            <div class="auth-stat">
                <strong>Avance</strong>
                <span>Seguimiento claro</span>
            </div>
        </div>
    </div>
</section>
<?php /**PATH C:\Users\t'chala\proyectos\plataforma-laravel\resources\views/components/auth/visual.blade.php ENDPATH**/ ?>