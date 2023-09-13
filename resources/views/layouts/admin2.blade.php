<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ config('app.name', 'Administración') }}</title>
    <link rel="shortcut icon" type="image/jpg" href="{{ Storage::url('img/eus-logo.png') }}" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- <link rel="stylesheet" href="{{ mix('css/app.css') }}"> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <link rel="stylesheet" href="{{ URL::asset('css/custom.css') }}" />

    @stack('csss')
    <!-- Scripts -->
</head>

<body id="myPage" class="bg-white" data-spy="scroll" data-target=".navbar" data-offset="60">
    <div class="container">
        {{ $slot ?? '' }}
    </div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    @stack('javascripts')
</body>

</html>