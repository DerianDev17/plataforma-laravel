<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="shortcut icon" type="image/jpg" href="{{ Storage::url('img/eus-logo.png') }}" />
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/custom.css') }}" />

    <style>
        @import url('https://fonts.googleapis.com/css?family=Karla:400,700&display=swap');

        .font-family-karla {
            font-family: karla;
        }

        .bg-sidebar {
            background: #3d68ff;
        }

        .cta-btn {
            color: #3d68ff;
        }

        .upgrade-btn {
            background: #1947ee;
        }

        .upgrade-btn:hover {
            background: #0038fd;
        }

        .active-nav-link {
            background: #1947ee;
        }

        .nav-item:hover {
            background: #1947ee;
        }

        .account-link:hover {
            background: #3d68ff;
        }
    </style>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    @livewireStyles
    @stack('estilos')
    @stack('estilos2')

    <script>
        var APP_URL = "{{URL::to('/')}}";
        console.log('APP_URL :>> ', APP_URL);
    </script>
    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
</head>

<body class="font-sans antialiased">

    <div class="min-h-screen bg-gray-100">
        @livewire('navigation-dropdown')

        <!-- Page Heading -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    @livewireScripts

    @stack('modals')

    <!-- global components -->
    @if (!Auth::user()->check_cedula_validity() && Auth::user()->cedula[0] != 'p')
    <x-ui.customised-modal>
        <x-slot name="content">
            @livewire('forms.user-update-form')
        </x-slot>
    </x-ui.customised-modal>
    @endif

    @if (!Auth::user()->cedulaPadre())
    <x-ui.customised-modal>
        <x-slot name="content">
            @livewire('forms.parents-update-form')
        </x-slot>
    </x-ui.customised-modal>
    @endif

    <script src="https://code.jquery.com/jquery-latest.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js" integrity="sha512-LGXaggshOkD/at6PFNcp2V2unf9LzFq6LE+sChH7ceMTDP0g2kn6Vxwgg7wkPP7AAtX+lmPqPdxB47A0Nz0cMQ==" crossorigin="anonymous"></script>

    <script>
        @if(isset($today_sessions))
        $(document).ready(function() {
            // flags, para enviar la notificacion solo una vez por cargado de la pagina
            let sentFlags = [false, false, false, false, false];

            // cada 5 segundos preguntar si la hora actual corresponde a alguna de las horas
            // del horario
            setInterval(() => {
                // hora actual
                let hora = moment().format('HH:mm');

                // se crea una condicion para cada una de las 5 clases del dia actual
                @foreach($today_sessions as $i => $session)
                if (hora == '{{$session[0]}}') {
                    if (sentFlags[{
                            {
                                $i
                            }
                        }] == false) {
                        let msg = 'Tiene clase de ' + '{{$session[1]}}' + ' - ' + '{{$session[0]}}';
                        mostrarNotif(msg);
                        sentFlags[{
                            {
                                $i
                            }
                        }] = true;
                    }
                }
                // console.log('hora_horario :>> ', '{{$session[0]}}');
                // console.log('hora_actual :>> ', hora);
                @endforeach
            }, 5000);
            @endif
        });

        function mostrarNotif(mensaje) {
            if (!("Notification" in window)) {
                alert("This browser does not support desktop notification");
            } else if (Notification.permission === "granted") {
                let img = '/storage/img/eus-logo.png';
                var notification = new Notification('Recordatorio.', {
                    body: mensaje,
                    icon: img
                });
            } else if (Notification.permission !== "denied") {
                Notification.requestPermission().then(function(permission) {
                    if (permission === "granted") {
                        var notification = new Notification(mensaje);
                    }
                });
            }
        }
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#btnModal").click();
        });
    </script>
    @stack('javascripts')

</body>

</html>