 <?php $__env->slot('header', null, []); ?> 
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Sesiones
    </h2>
 <?php $__env->endSlot(); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

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

    <button wire:click="create()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-10">Crear nueva sesión</button>
    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'jetstream::components.input','data' => ['type' => 'text','placeholder' => 'Buscar','wire:model' => 'searchTerm']]); ?>
<?php $component->withName('jet-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['type' => 'text','placeholder' => 'Buscar','wire:model' => 'searchTerm']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>

    <?php if(count($sesiones) > 0): ?>
    <div class="py-10">
        <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-black bg-black text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Materia
                        </th>
                        <th class="px-5 py-3 border-b-2 border-black bg-black text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Fecha y Hora
                        </th>
                        <th class="px-5 py-3 border-b-2 border-black bg-black text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Grupo
                        </th>
                        <th class="px-5 py-3 border-b-2 border-black bg-black text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Módulo
                        </th>
                        <th class="px-5 py-3 border-b-2 border-black bg-black text-left text-xs font-semibold text-white uppercase tracking-wider">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $sesiones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sesion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="px-5 py-5 bg-white text-sm <?php if(!$loop->last): ?> border-gray-200 border-b <?php endif; ?>">
                            <?php echo e(Str::limit($sesion->subject, 25)); ?>

                        </td>
                        <td class="px-5 py-5 bg-white text-sm <?php if(!$loop->last): ?> border-gray-200 border-b <?php endif; ?>">
                            <?php echo e(Str::limit($sesion->date, 25)); ?> <?php echo e(Str::limit($sesion->time, 25)); ?>

                        </td>
                        <td class="px-5 py-5 bg-white text-sm <?php if(!$loop->last): ?> border-gray-200 border-b <?php endif; ?>">
                            <?php echo e(optional($sesion->student_group)->code ?? '-'); ?>

                        </td>
                        <td class="px-5 py-5 bg-white text-sm <?php if(!$loop->last): ?> border-gray-200 border-b <?php endif; ?>">
                            <?php echo e(Str::limit($sesion->module_number, 25)); ?>

                        </td>
                        <td class="px-5 py-5 bg-white text-sm <?php if(!$loop->last): ?> border-gray-200 border-b <?php endif; ?> text-right">
                            <div class="inline-block whitespace-no-wrap">
                                <button wire:click="edit(<?php echo e($sesion->id); ?>)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Editar</button>
                                <button wire:click="$emit('triggerDelete',<?php echo e($sesion->id); ?>)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Borrar</button>
                            </div>
                        </td>

                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <?php echo e($sesiones->links('components.ui.pagination',['is_livewire' => true])); ?>

        </div>
    </div>
    <?php else: ?>
    <div class="py-10 text-center text-gray-500">
        <p class="text-lg">No hay sesiones registradas.</p>
    </div>
    <?php endif; ?>

    <?php if($isOpen): ?>
    <?php if (isset($component)) { $__componentOriginal9ebe92f465e636e92e2ebddfdb727e1d4f282236 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Ui\CustomisedModal::class, []); ?>
<?php $component->withName('ui.customised-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
         <?php $__env->slot('content', null, []); ?> 
            <?php $__errorArgs = ['session_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <form>
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-2/2 px-3 mb-6 md:mb-0">
                            <label for="titleInput" class="block text-gray-700 text-sm font-bold mb-2">Materia:</label>
                            <select class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="subject" placeholder="Materia" wire:model="subject">
                                <option value="" class="py-1" selected hidden>Seleccione una materia...</option>
                                <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($subject->id); ?>" class="py-1"><?php echo e($subject->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-2/2 px-3 mb-6 md:mb-0">
                            <label for="titleInput" class="block text-gray-700 text-sm font-bold mb-2">Fecha y hora de la sesión:</label>
                            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline datetime-session" id="datetime-session" placeholder="Fecha y hora" wire:model="datetime">
                            <?php $__errorArgs = ['datetime'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-2/2 px-3 mb-6 md:mb-0">
                            <label for="titleInput" class="block text-gray-700 text-sm font-bold mb-2">Grupo (paralelo):</label>
                            <select class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="titleInput" placeholder="Grupo" wire:model="course_id">
                                <option value="" class="py-1" selected hidden>Seleccione un grupo...</option>
                                <?php $__currentLoopData = $student_groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($grp->id); ?>" class="py-1"><?php echo e($grp->code); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['course_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-2/2 px-3 mb-6 md:mb-0">
                            <label for="titleInput" class="block text-gray-700 text-sm font-bold mb-2">Módulo:</label>
                            <select class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="titleInput" placeholder="Grupo" wire:model="module_number">
                                <option value="" class="py-1" selected hidden>Seleccione un módulo...</option>
                                <option value="1" class="py-1">Módulo 1</option>
                                <option value="2" class="py-1">Módulo 2</option>
                                <option value="3" class="py-1">Módulo 3</option>
                                <option value="4" class="py-1">Módulo 4</option>
                                <option value="5" class="py-1">Módulo 5</option>
                            </select>
                            <?php $__errorArgs = ['module_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <span class="flex w-full sm:ml-3 sm:w-auto">
                        <button wire:click.prevent="store()" type="button" class="inline-flex bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Guardar</button>
                    </span>
                    <span class="mt-3 flex w-full sm:mt-0 sm:w-auto">
                        <button wire:click="closeModal()" type="button" class="inline-flex bg-white hover:bg-gray-200 border border-gray-300 text-gray-500 font-bold py-2 px-4 rounded">Cancelar</button>
                    </span>
                </div>
            </form>
         <?php $__env->endSlot(); ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ebe92f465e636e92e2ebddfdb727e1d4f282236)): ?>
<?php $component = $__componentOriginal9ebe92f465e636e92e2ebddfdb727e1d4f282236; ?>
<?php unset($__componentOriginal9ebe92f465e636e92e2ebddfdb727e1d4f282236); ?>
<?php endif; ?>
        <?php endif; ?>
</div>

<?php $__env->startPush('estilos'); ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('javascripts'); ?>
<script defer src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        Livewire.on('triggerDelete', sessionId => {
            Swal.fire({
                title: 'Confirmar acción',
                text: '¡Se eliminará la sesión!',
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Eliminar'
            }).then((result) => {
                if (result.value) {
                    window.livewire.find('<?php echo e($_instance->id); ?>').call('delete', sessionId)
                } else {
                    console.log("Canceled");
                }
            });
        });
    });

    Livewire.on('modalOpened', () => {
        // date picker
        $(".datetime-session").flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\t'chala\proyectos\plataforma-laravel\resources\views/livewire/sesiones/show.blade.php ENDPATH**/ ?>