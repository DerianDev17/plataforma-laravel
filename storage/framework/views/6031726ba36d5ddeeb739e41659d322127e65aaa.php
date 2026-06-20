<?php if (isset($component)) { $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\AppLayout::class, []); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> Inicio <?php $__env->endSlot(); ?>

    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.ui.alert','data' => []]); ?>
<?php $component->withName('ui.alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>

    <?php if(!Auth::user()->email_verified_at): ?>
    <div class="eus-alert eus-alert-warning alert-with-action" role="status">
        <div>
            <strong>Correo pendiente de verificaci&oacute;n.</strong>
            <span>Su direcci&oacute;n de correo electr&oacute;nico a&uacute;n no ha sido verificada.</span>
        </div>
        <form method="POST" action="<?php echo e(route('verification.send')); ?>" class="alert-action">
            <?php echo csrf_field(); ?>
            <button type="submit" class="eus-btn eus-btn-sm eus-btn-secondary">
                Enviar verificaci&oacute;n
            </button>
        </form>
    </div>
    <?php endif; ?>

    <?php
if (! isset($_instance)) {
    $html = \Livewire\Livewire::mount('users.dashboard')->html();
} elseif ($_instance->childHasBeenRendered('HrKuOv1')) {
    $componentId = $_instance->getRenderedChildComponentId('HrKuOv1');
    $componentTag = $_instance->getRenderedChildComponentTagName('HrKuOv1');
    $html = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('HrKuOv1');
} else {
    $response = \Livewire\Livewire::mount('users.dashboard');
    $html = $response->html();
    $_instance->logRenderedChild('HrKuOv1', $response->id(), \Livewire\Livewire::getRootElementTagName($html));
}
echo $html;
?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>
<?php /**PATH C:\Users\t'chala\proyectos\plataforma-laravel\resources\views/dashboard.blade.php ENDPATH**/ ?>