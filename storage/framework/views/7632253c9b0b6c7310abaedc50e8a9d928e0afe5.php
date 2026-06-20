<div>

    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.ui.alert','data' => ['type' => 'info']]); ?>
<?php $component->withName('ui.alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['type' => 'info']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
    <?php if($isOpen): ?>
    <?php echo $__env->make('livewire.create', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'jetstream::components.input','data' => ['type' => 'text','placeholder' => 'Email','wire:model' => 'searchTerm']]); ?>
<?php $component->withName('jet-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['type' => 'text','placeholder' => 'Email','wire:model' => 'searchTerm']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'jetstream::components.input','data' => ['type' => 'text','placeholder' => 'Pagado','wire:model' => 'searchTerm2']]); ?>
<?php $component->withName('jet-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['type' => 'text','placeholder' => 'Pagado','wire:model' => 'searchTerm2']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
    <button wire:click="downloadStudents()" class="bg-grey-light hover:bg-grey text-grey-darkest font-bold py-2 px-4 rounded inline-flex items-center">
        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z" />
        </svg>
        <span>Descargar</span>
    </button>

    <div class="" style="overflow-x:auto;">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 w-20">No.</th>
                    <th class="px-4 py-2">Nombre</th>
                    <th class="px-4 py-2">Apellido</th>
                    <th class="px-4 py-2">Usuario</th>
                    <th class="px-4 py-2 w-10">Pagado</th>
                    <th class="px-4 py-2">Celular</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $studentsForTable; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($student->hasRole('student')): ?>
                <tr>
                    <td class="border px-4 py-2"><?php echo e($student->id); ?></td>
                    <td class="border px-4 py-2"><?php echo e($student->name); ?></td>
                    <td class="border px-4 py-2"><?php echo e($student->last_name); ?></td>
                    <td class="border px-4 py-2"><?php echo e($student->username); ?></td>
                    <td class="border px-4 py-2"><?php echo e($student->status); ?></td>
                    <td class="border px-4 py-2"><?php echo e($student->cellphone); ?></td>
                    <td class="border px-4 py-2"><?php echo e($student->email); ?></td>
                    <td class="border px-4 py-2">
                        <button wire:click="edit(<?php echo e($student->id); ?>)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">Editar</button>
                        <!-- <button onclick="borrarUser(<?php echo e($student->id); ?>)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Borrar</button> -->
                        <button wire:click="$emit('triggerDelete',<?php echo e($student->id); ?>)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">Eliminar</button>
                        <button wire:click="resetPassword(<?php echo e($student->id); ?>)" class="bg-orange-700 hover:bg-orange-800 text-white font-bold py-1 px-2 rounded">Reset pass.</button>
                    </td>
                </tr>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <?php echo e($studentsForTable->links('components.ui.pagination',['is_livewire' => true])); ?>

</div>


<?php $__env->startPush('javascripts'); ?>

<script defer src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        // console.log(companyId)
        Livewire.on('triggerDelete', studentId => {
            Swal.fire({
                title: 'Confirmar acción',
                text: 'El estudiante será eliminado.',
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '¡Borrar!',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.value) {
                    window.livewire.find('<?php echo e($_instance->id); ?>').call('delete', studentId)
                } else {
                    console.log("Canceled");
                }
            });
        })
    })
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\t'chala\proyectos\plataforma-laravel\resources\views/livewire/users-crud/show.blade.php ENDPATH**/ ?>