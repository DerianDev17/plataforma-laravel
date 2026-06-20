<?php
    $scheduleDays = [
        ['day' => 1, 'label' => 'Lunes', 'time' => 0, 'subject' => 1],
        ['day' => 2, 'label' => 'Martes', 'time' => 0, 'subject' => 2],
        ['day' => 3, 'label' => 'Mi&eacute;rcoles', 'time' => 0, 'subject' => 3],
        ['day' => 4, 'label' => 'Jueves', 'time' => 0, 'subject' => 4],
        ['day' => 5, 'label' => 'Viernes', 'time' => 0, 'subject' => 5],
        ['day' => 6, 'label' => 'S&aacute;bado', 'time' => 6, 'subject' => 7],
    ];
?>

<div class="dashboard-stack meetings-page">
    <?php if($show_alert && session()->has('error')): ?>
        <div class="eus-alert eus-alert-danger" role="alert">
            <span><?php echo e(session('error')); ?></span>
            <button type="button" class="eus-btn eus-btn-ghost eus-btn-sm" wire:click="closeAlert" aria-label="Cerrar alerta">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if($show_alert && session()->has('ok')): ?>
        <div class="eus-alert eus-alert-success" role="status">
            <span><?php echo e(session('ok')); ?></span>
            <button type="button" class="eus-btn eus-btn-ghost eus-btn-sm" wire:click="closeAlert" aria-label="Cerrar alerta">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <section class="dashboard-hero meetings-hero" aria-label="Resumen de reuniones">
        <div class="dashboard-hero-body">
            <div>
                <p class="dashboard-eyebrow">Clases en vivo</p>
                <h2 class="dashboard-title">Reuniones y horario semanal</h2>
                <p class="dashboard-copy">
                    Revisa las clases del dia y consulta el horario por paralelo desde un solo lugar.
                </p>
                <?php if($studentGroupCode && $studentGroupCode !== 'Z'): ?>
                    <span class="eus-badge badge-on-dark">Paralelo <?php echo e($studentGroupCode); ?></span>
                <?php else: ?>
                    <span class="eus-badge badge-on-dark">Vista administrativa</span>
                <?php endif; ?>
            </div>

            <div class="dashboard-mark" aria-hidden="true">
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.ui.icon','data' => ['name' => 'calendar','size' => 38]]); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'calendar','size' => 38]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
            </div>
        </div>
    </section>

    <section class="eus-card" aria-label="Sesiones de hoy">
        <div class="eus-card-header">
            <div>
                <h3 class="eus-card-title">Sesiones de hoy</h3>
                <p class="class-schedule-subtitle">
                    <?php echo e(now()->isoFormat('dddd, D [de] MMMM')); ?>

                </p>
            </div>
            <span class="eus-badge eus-badge-gray"><?php echo e(count($today_sessions)); ?></span>
        </div>

        <?php if(count($today_sessions)): ?>
            <div class="meeting-session-grid">
                <?php $__currentLoopData = $today_sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <article class="meeting-session-card">
                        <div>
                            <span class="meeting-session-time"><?php echo e($session[0] ?? '--:--'); ?></span>
                            <h4><?php echo e($session[1] ?? 'Clase por confirmar'); ?></h4>
                            <p><?php echo e($studentGroupName ?? 'Paralelo asignado'); ?></p>
                        </div>

                        <?php if($session['asistio'] ?? false): ?>
                            <span class="eus-badge eus-badge-green">Registrada</span>
                        <?php else: ?>
                            <button
                                type="button"
                                class="eus-btn eus-btn-primary"
                                wire:click="registAttendance(<?php echo \Illuminate\Support\Js::from($session[1] ?? '')->toHtml() ?>, <?php echo \Illuminate\Support\Js::from($session[0] ?? '')->toHtml() ?>)"
                            >
                                Registrar asistencia
                            </button>
                        <?php endif; ?>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="eus-empty empty-compact">
                <div class="eus-empty-icon">
                    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.ui.icon','data' => ['name' => 'calendar','size' => 32]]); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'calendar','size' => 32]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                </div>
                <div class="eus-empty-title">Sin sesiones para hoy</div>
                <div class="eus-empty-text">
                    <?php if($studentGroupCode && $studentGroupCode !== 'Z'): ?>
                        No hay clases registradas para tu paralelo en este dia.
                    <?php else: ?>
                        La vista administrativa muestra los horarios semanales por paralelo abajo.
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </section>

    <section class="meeting-schedule-stack" aria-label="Horarios semanales por paralelo">
        <?php $__empty_1 = true; $__currentLoopData = $scheduleGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <article class="eus-card meeting-schedule-card">
                <div class="eus-card-header class-schedule-header">
                    <div>
                        <h3 class="eus-card-title"><?php echo e($group['name']); ?></h3>
                        <p class="class-schedule-subtitle">Horario semanal de clases en vivo</p>
                    </div>
                    <span class="eus-badge eus-badge-orange">Paralelo <?php echo e($group['code']); ?></span>
                </div>

                <div class="class-schedule-wrapper" tabindex="0" aria-label="Horario semanal del paralelo <?php echo e($group['code']); ?>">
                    <table class="eus-table class-schedule-table">
                        <thead>
                            <tr>
                                <?php $__currentLoopData = $scheduleDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <th scope="col" class="<?php echo e($currentScheduleDay === $day['day'] ? 'is-today' : ''); ?>">
                                        <span class="class-day-label"><?php echo $day['label']; ?></span>
                                    </th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $group['schedule']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <?php $__currentLoopData = $scheduleDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <td class="<?php echo e($currentScheduleDay === $day['day'] ? 'is-today' : ''); ?>">
                                            <span class="class-time"><?php echo e($row[$day['time']] ?? '--:--'); ?></span>
                                            <?php if($zoom_link): ?>
                                                <a class="class-subject meeting-open-link" href="<?php echo e($zoom_link); ?>" target="_blank" rel="noopener">
                                                    <?php echo e($row[$day['subject']] ?? 'Clase por confirmar'); ?>

                                                </a>
                                            <?php else: ?>
                                                <span class="class-subject"><?php echo e($row[$day['subject']] ?? 'Clase por confirmar'); ?></span>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <article class="eus-card">
                <div class="eus-empty empty-compact">
                    <div class="eus-empty-icon">
                        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.ui.icon','data' => ['name' => 'calendar','size' => 32]]); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'calendar','size' => 32]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                    </div>
                    <div class="eus-empty-title">Horario no disponible</div>
                    <div class="eus-empty-text">Asigna un paralelo al estudiante para mostrar sus clases.</div>
                </div>
            </article>
        <?php endif; ?>
    </section>
</div>
<?php /**PATH C:\Users\t'chala\proyectos\plataforma-laravel\resources\views/livewire/meetings/show.blade.php ENDPATH**/ ?>