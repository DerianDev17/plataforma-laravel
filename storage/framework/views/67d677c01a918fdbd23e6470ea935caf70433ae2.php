<?php if (isset($component)) { $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\AppLayout::class, []); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           Configuración Zoom
        </h2>
     <?php $__env->endSlot(); ?>

        <?php
if (! isset($_instance)) {
    $html = \Livewire\Livewire::mount('zoom-configuration.show')->html();
} elseif ($_instance->childHasBeenRendered('u4PMhM7')) {
    $componentId = $_instance->getRenderedChildComponentId('u4PMhM7');
    $componentTag = $_instance->getRenderedChildComponentTagName('u4PMhM7');
    $html = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('u4PMhM7');
} else {
    $response = \Livewire\Livewire::mount('zoom-configuration.show');
    $html = $response->html();
    $_instance->logRenderedChild('u4PMhM7', $response->id(), \Livewire\Livewire::getRootElementTagName($html));
}
echo $html;
?>
                
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>
<?php /**PATH C:\Users\t'chala\proyectos\plataforma-laravel\resources\views/pages/zoom-configuration.blade.php ENDPATH**/ ?>