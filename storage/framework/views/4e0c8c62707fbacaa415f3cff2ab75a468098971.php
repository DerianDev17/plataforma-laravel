<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="description" content="<?php echo e($description ?? 'Plataforma academica Semilla Digital para estudiantes, clases, recursos y gestion administrativa.'); ?>">

    <title><?php echo e($title ?? config('app.name', 'Semilla Digital') . ' | ' . ($header ?? 'Dashboard')); ?></title>
    <link rel="icon" type="image/svg+xml" href="<?php echo e(asset('brand/semilla-logo-mark.svg')); ?>">

    <link rel="preload" href="<?php echo e(asset('fonts/manrope-latin.woff2')); ?>" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?php echo e(asset('fonts/fraunces-latin.woff2')); ?>" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="<?php echo e(asset('fonts/semilla-fonts.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(URL::asset('css/modern.css')); ?>" />

    <?php echo \Livewire\Livewire::styles(); ?>

    <?php echo $__env->yieldPushContent('estilos'); ?>
    <?php echo $__env->yieldPushContent('estilos2'); ?>

    <script src="<?php echo e(asset('js/alpine.min.js')); ?>" defer></script>
    <script src="<?php echo e(asset('js/semilla-admin.js')); ?>" defer></script>

    <?php if(isset($today_sessions)): ?>
    <script>
        window.todaySessions = <?php echo json_encode($today_sessions, 15, 512) ?>;
    </script>
    <?php endif; ?>
</head>

<body class="antialiased">
    <a href="#main-content" class="skip-link">Saltar al contenido principal</a>

    <div wire:offline class="eus-alert eus-alert-danger offline-alert">
        <span>Sin conexion. Por favor revise su internet.</span>
    </div>

    <div wire:loading class="livewire-loading-bar"></div>

    <div class="app-shell">
        <div class="sidebar-overlay" data-sidebar-overlay aria-hidden="true"></div>

        <aside class="app-sidebar" data-sidebar role="navigation" aria-label="Navegacion principal">
            <div class="sidebar-brand">
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.brand.logo','data' => ['class' => 'brand-logo-sidebar']]); ?>
<?php $component->withName('brand.logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['class' => 'brand-logo-sidebar']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                <span class="sidebar-brand-text"><?php echo e(config('app.name', 'Semilla Digital')); ?></span>
            </div>

            <nav class="sidebar-nav" aria-label="Menu principal">
                <div class="sidebar-section">
                    <div class="sidebar-section-title">Principal</div>
                    <a href="<?php echo e(route('dashboard')); ?>" class="sidebar-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
                        <span class="sidebar-icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        </span>
                        Inicio
                    </a>
                    <a href="<?php echo e(route('informacion')); ?>" class="sidebar-link <?php echo e(request()->routeIs('informacion') ? 'active' : ''); ?>">
                        <span class="sidebar-icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                        </span>
                        Panel Informativo
                    </a>
                    <a href="<?php echo e(route('dashboard-meetings')); ?>" class="sidebar-link <?php echo e(request()->routeIs('dashboard-meetings') ? 'active' : ''); ?>">
                        <span class="sidebar-icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                        </span>
                        Reuniones
                    </a>
                </div>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit_users')): ?>
                <div class="sidebar-section">
                    <div class="sidebar-section-title">Administracion</div>
                    <a href="<?php echo e(route('dashboard-students')); ?>" class="sidebar-link <?php echo e(request()->routeIs('dashboard-students') ? 'active' : ''); ?>">
                        <span class="sidebar-icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4-4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                        </span>
                        Estudiantes
                    </a>
                    <a href="<?php echo e(route('users-crud')); ?>" class="sidebar-link <?php echo e(request()->routeIs('users-crud') ? 'active' : ''); ?>">
                        <span class="sidebar-icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4-4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </span>
                        Usuarios
                    </a>
                    <a href="<?php echo e(route('asistencias-crud')); ?>" class="sidebar-link <?php echo e(request()->routeIs('asistencias-crud') ? 'active' : ''); ?>">
                        <span class="sidebar-icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        </span>
                        Asistencias
                    </a>
                    <a href="<?php echo e(route('sesiones-crud')); ?>" class="sidebar-link <?php echo e(request()->routeIs('sesiones-crud') ? 'active' : ''); ?>">
                        <span class="sidebar-icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </span>
                        Sesiones
                    </a>
                    <a href="<?php echo e(route('zoom-configuration')); ?>" class="sidebar-link <?php echo e(request()->routeIs('zoom-configuration') ? 'active' : ''); ?>">
                        <span class="sidebar-icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
                        </span>
                        Zoom
                    </a>
                    <a href="<?php echo e(route('get.register_participants')); ?>" class="sidebar-link <?php echo e(request()->routeIs('get.register_participants') ? 'active' : ''); ?>">
                        <span class="sidebar-icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4-4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                        </span>
                        Registro Zoom
                    </a>
                    <a href="<?php echo e(route('actualizar_base.get')); ?>" class="sidebar-link <?php echo e(request()->routeIs('actualizar_base.get') ? 'active' : ''); ?>">
                        <span class="sidebar-icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        </span>
                        Actualizar Base
                    </a>
                </div>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crud_drives')): ?>
                <div class="sidebar-section">
                    <div class="sidebar-section-title">Recursos</div>
                    <a href="<?php echo e(route('drives')); ?>" class="sidebar-link <?php echo e(request()->routeIs('drives') ? 'active' : ''); ?>">
                        <span class="sidebar-icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/></svg>
                        </span>
                        Drives
                    </a>
                </div>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('read_course_programs')): ?>
                <?php if(Auth::user()->student_group_id != 3): ?>
                <div class="sidebar-section">
                    <div class="sidebar-section-title">Herramientas</div>
                    <a href="<?php echo e(route('course_programm')); ?>" class="sidebar-link <?php echo e(request()->routeIs('course_programm') ? 'active' : ''); ?>">
                        <span class="sidebar-icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                        </span>
                        Transformar
                    </a>
                </div>
                <?php endif; ?>
                <?php endif; ?>

                <div class="sidebar-section">
                    <div class="sidebar-section-title">Academico</div>
                    <a href="<?php echo e(route('dashboard-asistencias')); ?>" class="sidebar-link <?php echo e(request()->routeIs('dashboard-asistencias') ? 'active' : ''); ?>">
                        <span class="sidebar-icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        </span>
                        Mis asistencias
                    </a>
                    <a href="<?php echo e(route('recursos')); ?>" class="sidebar-link <?php echo e(request()->routeIs('recursos') ? 'active' : ''); ?>">
                        <span class="sidebar-icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>
                        </span>
                        Clases Grabadas
                    </a>
                    <a href="<?php echo e(route('material_digital')); ?>" class="sidebar-link <?php echo e(request()->routeIs('material_digital') ? 'active' : ''); ?>">
                        <span class="sidebar-icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
                        </span>
                        Material Digital
                    </a>
                </div>
            </nav>

            <div class="sidebar-footer">
                <div class="sidebar-user" role="button" tabindex="0" aria-label="Menu de usuario" aria-haspopup="true">
                    <div class="sidebar-user-avatar" aria-hidden="true">
                        <?php echo e(strtoupper(substr(Auth::user()->name ?? 'U', 0, 2))); ?>

                    </div>
                    <div class="sidebar-user-info">
                        <div class="sidebar-user-name"><?php echo e(Auth::user()->name); ?></div>
                        <div class="sidebar-user-role"><?php echo e(Auth::user()->roles->first()?->name ?? 'Usuario'); ?></div>
                    </div>
                </div>
            </div>
        </aside>

        <main class="app-main" id="main-content">
            <header class="app-topbar">
                <div class="topbar-left">
                    <button
                        class="menu-toggle"
                        data-sidebar-toggle
                        aria-label="Alternar menu de navegacion"
                        aria-expanded="false"
                    >
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <line x1="3" y1="12" x2="21" y2="12" />
                            <line x1="3" y1="18" x2="21" y2="18" />
                        </svg>
                    </button>
                    <?php if(isset($header)): ?>
                    <h1 class="page-title page-title-reset"><?php echo e($header); ?></h1>
                    <?php else: ?>
                    <h1 class="page-title page-title-reset"><?php echo e(config('app.name', 'Semilla Digital')); ?></h1>
                    <?php endif; ?>
                </div>

                <div class="topbar-right">
                    <a href="<?php echo e(route('profile.show')); ?>" class="eus-btn eus-btn-ghost eus-btn-icon" aria-label="Perfil de usuario" title="Perfil">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4-4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                    </a>
                    <form method="POST" action="<?php echo e(route('logout')); ?>" id="logout-form">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="eus-btn eus-btn-ghost eus-btn-icon" aria-label="Cerrar sesion" title="Salir">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </header>

            <div class="page-content">
                <?php echo e($slot); ?>

            </div>
        </main>
    </div>

    <?php echo \Livewire\Livewire::scripts(); ?>

    <?php echo $__env->yieldPushContent('modals'); ?>
    <?php echo $__env->yieldPushContent('javascripts'); ?>
</body>

</html>
<?php /**PATH C:\Users\t'chala\proyectos\plataforma-laravel\resources\views/layouts/admin.blade.php ENDPATH**/ ?>