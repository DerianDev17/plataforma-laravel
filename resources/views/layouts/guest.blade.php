<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $description ?? 'Acceso a la plataforma academica Semilla Digital para estudiantes y equipo administrativo.' }}">

    <title>{{ $title ?? config('app.name', 'Semilla Digital') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('brand/semilla-logo-mark.svg') }}">

    <link rel="preload" href="{{ asset('fonts/manrope-latin.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('fonts/fraunces-latin.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="{{ asset('fonts/semilla-fonts.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/modern.css') }}" />

    @stack('estilos')
</head>

<body class="antialiased guest-bg">
    <a href="#guest-content" class="skip-link">Saltar al contenido principal</a>

    <main id="guest-content">
        {{ $slot }}
    </main>

    @stack('javascripts')
</body>

</html>
