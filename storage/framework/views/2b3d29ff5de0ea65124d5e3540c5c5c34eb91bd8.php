<?php if (isset($component)) { $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\AppLayout::class, []); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Estudiantes
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
    <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                <?php
if (! isset($_instance)) {
    $html = \Livewire\Livewire::mount('students.show')->html();
} elseif ($_instance->childHasBeenRendered('8HNFoxh')) {
    $componentId = $_instance->getRenderedChildComponentId('8HNFoxh');
    $componentTag = $_instance->getRenderedChildComponentTagName('8HNFoxh');
    $html = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('8HNFoxh');
} else {
    $response = \Livewire\Livewire::mount('students.show');
    $html = $response->html();
    $_instance->logRenderedChild('8HNFoxh', $response->id(), \Livewire\Livewire::getRootElementTagName($html));
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
<?php endif; ?>
<?php /**PATH C:\Users\t'chala\proyectos\plataforma-laravel\resources\views/pages/students.blade.php ENDPATH**/ ?>