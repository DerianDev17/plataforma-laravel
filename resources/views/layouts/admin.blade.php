<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $description ?? 'Plataforma academica Semilla Digital para estudiantes, clases, recursos y gestion administrativa.' }}">

    <title>{{ $title ?? config('app.name', 'Semilla Digital') . ' | ' . ($header ?? 'Dashboard') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('brand/semilla-logo-mark.svg') }}">

    <link rel="preload" href="{{ asset('fonts/manrope-latin.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('fonts/fraunces-latin.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="{{ asset('fonts/semilla-fonts.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/modern.css') }}" />

    @livewireStyles
    @stack('estilos')
    @stack('estilos2')

    <script src="{{ asset('js/semilla-admin.js') }}" defer></script>

    @if(isset($today_sessions))
    <script>
        window.todaySessions = @json($today_sessions);
    </script>
    @endif
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
                <x-brand.logo class="brand-logo-sidebar" />
                <span class="sidebar-brand-text">{{ config('app.name', 'Semilla Digital') }}</span>
            </div>

            <nav class="sidebar-nav" aria-label="Menu principal">
                <div class="sidebar-section">
                    <div class="sidebar-section-title">Principal</div>
                    <a wire:navigate href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <span class="sidebar-icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        </span>
                        Inicio
                    </a>
                    <a wire:navigate href="{{ route('informacion') }}" class="sidebar-link {{ request()->routeIs('informacion') ? 'active' : '' }}">
                        <span class="sidebar-icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                        </span>
                        Panel Informativo
                    </a>
                    <a wire:navigate href="{{ route('dashboard-meetings') }}" class="sidebar-link {{ request()->routeIs('dashboard-meetings') ? 'active' : '' }}">
                        <span class="sidebar-icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                        </span>
                        Reuniones
                    </a>
                </div>

                @can('edit_users')
                <div class="sidebar-section">
                    <div class="sidebar-section-title">Administracion</div>
                    <a wire:navigate href="{{ route('dashboard-students') }}" class="sidebar-link {{ request()->routeIs('dashboard-students') ? 'active' : '' }}">
                        <span class="sidebar-icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4-4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                        </span>
                        Estudiantes
                    </a>
                    <a wire:navigate href="{{ route('users-crud') }}" class="sidebar-link {{ request()->routeIs('users-crud') ? 'active' : '' }}">
                        <span class="sidebar-icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4-4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </span>
                        Usuarios
                    </a>
                    <a wire:navigate href="{{ route('asistencias-crud') }}" class="sidebar-link {{ request()->routeIs('asistencias-crud') ? 'active' : '' }}">
                        <span class="sidebar-icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        </span>
                        Asistencias
                    </a>
                    <a wire:navigate href="{{ route('sesiones-crud') }}" class="sidebar-link {{ request()->routeIs('sesiones-crud') ? 'active' : '' }}">
                        <span class="sidebar-icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </span>
                        Sesiones
                    </a>
                    <a wire:navigate href="{{ route('live-class-provider') }}" class="sidebar-link {{ request()->routeIs('live-class-provider') || request()->routeIs('zoom-configuration') ? 'active' : '' }}">
                        <span class="sidebar-icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
                        </span>
                        Proveedor clases
                    </a>
                    <a wire:navigate href="{{ route('actualizar_base.get') }}" class="sidebar-link {{ request()->routeIs('actualizar_base.get') ? 'active' : '' }}">
                        <span class="sidebar-icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        </span>
                        Actualizar Base
                    </a>
                </div>
                @endcan

                @can('crud_drives')
                <div class="sidebar-section">
                    <div class="sidebar-section-title">Recursos</div>
                    <a wire:navigate href="{{ route('drives') }}" class="sidebar-link {{ request()->routeIs('drives') ? 'active' : '' }}">
                        <span class="sidebar-icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/></svg>
                        </span>
                        Drives
                    </a>
                </div>
                @endcan

                @can('read_course_programs')
                @if (Auth::user()->student_group_id != 3)
                <div class="sidebar-section">
                    <div class="sidebar-section-title">Herramientas</div>
                    <a wire:navigate href="{{ route('course_programm') }}" class="sidebar-link {{ request()->routeIs('course_programm') ? 'active' : '' }}">
                        <span class="sidebar-icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                        </span>
                        Transformar
                    </a>
                </div>
                @endif
                @endcan

                <div class="sidebar-section">
                    <div class="sidebar-section-title">Academico</div>
                    <a wire:navigate href="{{ route('dashboard-asistencias') }}" class="sidebar-link {{ request()->routeIs('dashboard-asistencias') ? 'active' : '' }}">
                        <span class="sidebar-icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        </span>
                        Mis asistencias
                    </a>
                    <a wire:navigate href="{{ route('recursos') }}" class="sidebar-link {{ request()->routeIs('recursos') ? 'active' : '' }}">
                        <span class="sidebar-icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>
                        </span>
                        Clases Grabadas
                    </a>
                    <a wire:navigate href="{{ route('material_digital') }}" class="sidebar-link {{ request()->routeIs('material_digital') ? 'active' : '' }}">
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
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
                    </div>
                    <div class="sidebar-user-info">
                        <div class="sidebar-user-name">{{ Auth::user()->name }}</div>
                        <div class="sidebar-user-role">{{ Auth::user()->roles->first()?->name ?? 'Usuario' }}</div>
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
                    @if(isset($header))
                    <h1 class="page-title page-title-reset">{{ $header }}</h1>
                    @else
                    <h1 class="page-title page-title-reset">{{ config('app.name', 'Semilla Digital') }}</h1>
                    @endif
                </div>

                <div class="topbar-right">
                    <a href="{{ route('profile.show') }}" class="eus-btn eus-btn-ghost eus-btn-icon" aria-label="Perfil de usuario" title="Perfil">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4-4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <button type="submit" class="eus-btn eus-btn-ghost eus-btn-icon" aria-label="Cerrar sesion" title="Salir">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </header>

            <div class="page-content">
                {{ $slot }}
            </div>
        </main>
    </div>

    @livewireScripts
    @stack('modals')
    @stack('javascripts')
</body>

</html>
