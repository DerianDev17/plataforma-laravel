<?php if (isset($component)) { $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\AppLayout::class, []); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Sesiones
        </h2>
     <?php $__env->endSlot(); ?>



    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                <?php
if (! isset($_instance)) {
    $html = \Livewire\Livewire::mount('sesiones.show')->html();
} elseif ($_instance->childHasBeenRendered('QxXvMhP')) {
    $componentId = $_instance->getRenderedChildComponentId('QxXvMhP');
    $componentTag = $_instance->getRenderedChildComponentTagName('QxXvMhP');
    $html = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('QxXvMhP');
} else {
    $response = \Livewire\Livewire::mount('sesiones.show');
    $html = $response->html();
    $_instance->logRenderedChild('QxXvMhP', $response->id(), \Livewire\Livewire::getRootElementTagName($html));
}
echo $html;
?>
            </div>
        </div>
    </div>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?><?php /**PATH C:\Users\t'chala\proyectos\plataforma-laravel\resources\views/pages/sesiones.blade.php ENDPATH**/ ?>