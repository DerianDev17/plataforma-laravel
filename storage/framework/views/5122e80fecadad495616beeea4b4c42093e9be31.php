<?php
    $activePercent = $totalStudents > 0 ? min(100, round(($activeStudents / $totalStudents) * 100)) : 0;
    $stats = [
        [
            'variant' => 'info',
            'icon' => 'users',
            'value' => number_format($totalStudents),
            'label' => 'Estudiantes totales',
            'progress' => $activePercent,
            'note' => "{$activePercent}% activos",
        ],
        [
            'variant' => 'success',
            'icon' => 'check',
            'value' => number_format($activeStudents),
            'label' => 'Estudiantes activos',
            'progress' => $activePercent,
            'note' => 'de ' . number_format($totalStudents) . ' total',
        ],
        [
            'variant' => 'purple',
            'icon' => 'calendar',
            'value' => number_format($todaySessions),
            'label' => 'Sesiones hoy',
        ],
        [
            'variant' => 'orange',
            'icon' => 'bell',
            'value' => number_format($todayAttendances),
            'label' => 'Asistencias hoy',
        ],
    ];

    $actions = [
        ['route' => 'informacion', 'icon' => 'monitor', 'label' => 'Panel informativo'],
        ['route' => 'dashboard-meetings', 'icon' => 'monitor', 'label' => 'Reuniones Zoom'],
        ['route' => 'recursos', 'icon' => 'video', 'label' => 'Clases grabadas'],
        ['route' => 'material_digital', 'icon' => 'book', 'label' => 'Material digital'],
    ];

    $scheduleDays = [
        ['day' => 1, 'label' => 'Lunes', 'time' => 0, 'subject' => 1],
        ['day' => 2, 'label' => 'Martes', 'time' => 0, 'subject' => 2],
        ['day' => 3, 'label' => 'Mi&eacute;rcoles', 'time' => 0, 'subject' => 3],
        ['day' => 4, 'label' => 'Jueves', 'time' => 0, 'subject' => 4],
        ['day' => 5, 'label' => 'Viernes', 'time' => 0, 'subject' => 5],
        ['day' => 6, 'label' => 'S&aacute;bado', 'time' => 6, 'subject' => 7],
    ];
?>

<div class="dashboard-stack">
    <section class="dashboard-hero" aria-label="Resumen Semilla Digital">
        <div class="dashboard-hero-body">
            <div>
                <p class="dashboard-eyebrow"><?php echo e(now()->isoFormat('dddd, D [de] MMMM [de] YYYY')); ?></p>
                <h2 class="dashboard-title"><?php echo e($greeting); ?>, <?php echo e($userName); ?></h2>
                <p class="dashboard-copy">
                    Semilla Digital concentra tus clases, recursos y seguimiento acad&eacute;mico para que cada avance sea f&aacute;cil de revisar.
                </p>
                <span class="eus-badge badge-on-dark"><?php echo e($userRole); ?></span>
            </div>

            <div class="dashboard-mark" aria-hidden="true">
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.ui.icon','data' => ['name' => 'sprout','size' => 38]]); ?>
<?php $component->withName('ui.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'sprout','size' => 38]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
            </div>
        </div>
    </section>

    <section class="dashboard-grid" aria-label="Indicadores principales">
        <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.dashboard.stat-card','data' => ['variant' => $stat['variant'],'icon' => $stat['icon'],'value' => $stat['value'],'label' => $stat['label'],'progress' => $stat['progress'] ?? null,'note' => $stat['note'] ?? null]]); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['variant' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($stat['variant']),'icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($stat['icon']),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($stat['value']),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($stat['label']),'progress' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($stat['progress'] ?? null),'note' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($stat['note'] ?? null)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </section>

    <article class="eus-card class-schedule-card">
        <div class="eus-card-header class-schedule-header">
            <div>
                <h3 class="eus-card-title">Horario de clases</h3>
                <p class="class-schedule-subtitle">
                    <?php echo e($studentGroupName ? $studentGroupName : 'Sin paralelo asignado'); ?>

                </p>
            </div>
            <div class="class-schedule-actions">
                <span class="eus-badge <?php echo e($studentGroupCode && $studentGroupCode !== 'Z' ? 'eus-badge-orange' : 'eus-badge-gray'); ?>">
                    <?php echo e($studentGroupCode && $studentGroupCode !== 'Z' ? 'Paralelo ' . $studentGroupCode : 'Pendiente'); ?>

                </span>
                <a href="<?php echo e(route('dashboard-meetings')); ?>" class="eus-btn eus-btn-sm eus-btn-secondary">Reuniones</a>
            </div>
        </div>

        <?php if(!empty($classSchedule)): ?>
            <div class="class-schedule-wrapper" tabindex="0" aria-label="Tabla semanal de clases">
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
                        <?php $__currentLoopData = $classSchedule; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <?php $__currentLoopData = $scheduleDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <td class="<?php echo e($currentScheduleDay === $day['day'] ? 'is-today' : ''); ?>">
                                        <span class="class-time"><?php echo e($row[$day['time']] ?? '--:--'); ?></span>
                                        <span class="class-subject"><?php echo e($row[$day['subject']] ?? 'Clase por confirmar'); ?></span>
                                    </td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="eus-card-body">
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
                    <div class="eus-empty-text">Asigna un paralelo al usuario para mostrar sus clases semanales.</div>
                </div>
            </div>
        <?php endif; ?>
    </article>

    <section class="dashboard-two-col" aria-label="Accesos y sesiones">
        <article class="eus-card">
            <div class="eus-card-header">
                <h3 class="eus-card-title">Acciones r&aacute;pidas</h3>
            </div>
            <div class="eus-card-body quick-actions-grid">
                <?php $__currentLoopData = $actions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.dashboard.action-link','data' => ['href' => route($action['route']),'icon' => $action['icon']]]); ?>
<?php $component->withName('dashboard.action-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route($action['route'])),'icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($action['icon'])]); ?>
                        <?php echo e($action['label']); ?>

                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </article>

        <article class="eus-card">
            <div class="eus-card-header">
                <h3 class="eus-card-title">Pr&oacute;ximas sesiones</h3>
                <span class="eus-badge eus-badge-orange">7 d&iacute;as</span>
            </div>
            <div class="eus-card-body flush-card-body">
                <?php if($upcomingSessions && $upcomingSessions->count()): ?>
                    <div class="session-list">
                        <?php $__currentLoopData = $upcomingSessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="session-list-item">
                            <div class="date-token">
                                <?php echo e(\Carbon\Carbon::parse($session->date)->format('d')); ?>

                            </div>
                            <div class="min-w-0">
                                <div class="session-title text-truncate">
                                    <?php echo e($session->subject ?? 'Sesion sin titulo'); ?>

                                </div>
                                <div class="session-meta">
                                    <?php echo e(\Carbon\Carbon::parse($session->date)->isoFormat('ddd D MMM')); ?> &middot; <?php echo e($session->time ?? '--:--'); ?>

                                </div>
                            </div>
                        </div>
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
                        <div class="eus-empty-title">Sin sesiones pr&oacute;ximas</div>
                        <div class="eus-empty-text">No hay sesiones programadas para los pr&oacute;ximos 7 d&iacute;as.</div>
                    </div>
                <?php endif; ?>
            </div>
        </article>
    </section>

    <?php if($recentStudents && $recentStudents->count()): ?>
    <article class="eus-card">
        <div class="eus-card-header">
            <h3 class="eus-card-title">Estudiantes recientes</h3>
        </div>
        <div class="eus-table-wrapper">
            <table class="eus-table">
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Email</th>
                        <th>Estado</th>
                        <th>Registro</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $recentStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <div class="student-cell">
                                <div class="avatar-token" aria-hidden="true">
                                    <?php echo e(strtoupper(substr($student->name, 0, 2))); ?>

                                </div>
                                <span class="font-strong"><?php echo e($student->name); ?> <?php echo e($student->last_name ?? ''); ?></span>
                            </div>
                        </td>
                        <td><?php echo e($student->email); ?></td>
                        <td>
                            <?php if($student->status): ?>
                                <span class="eus-badge eus-badge-green">Activo</span>
                            <?php else: ?>
                                <span class="eus-badge eus-badge-gray">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td class="muted-small">
                            <?php echo e($student->created_at->isoFormat('D MMM YYYY')); ?>

                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </article>
    <?php endif; ?>
</div>
<?php /**PATH C:\Users\t'chala\proyectos\plataforma-laravel\resources\views/livewire/users/dashboard.blade.php ENDPATH**/ ?>