<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Semilla Digital</title>
</head>
<body>
    <h1>{{ $details['title'] }}</h1>

    <img
        src="{{ URL::to('/brand/semilla-logo-horizontal.svg') }}"
        alt="Semilla Digital"
        style="width:50%; max-width:320px;"
    >

    <p>Reciba un cordial saludo de parte del equipo de Semilla Digital.</p>

    @if (! empty($details['temp_password']))
        <p>
            Le informamos que se le ha generado un nombre de usuario y una contrase&ntilde;a temporal para que pueda acceder
            a la p&aacute;gina web. Deber&aacute; acceder con los siguientes datos:
        </p>
    @else
        <p>
            Le recordamos su nombre de usuario para acceder a la plataforma. Su contrase&ntilde;a actual no cambia.
        </p>
    @endif

    <p>
        <strong>Nombre de usuario:</strong> {{ $details['user']->username }} <br>
        @if (! empty($details['temp_password']))
            <strong>Contrase&ntilde;a temporal:</strong> {{ $details['temp_password'] }} <br>
        @endif
    </p>

    @if (! empty($details['temp_password']))
        <p>
            <strong>Importante:</strong> por seguridad, el sistema le solicitar&aacute; cambiar esta
            contrase&ntilde;a temporal antes de continuar. Este cambio lo puede hacer
            desde la secci&oacute;n del perfil de usuario.
        </p>
    @else
        <p>
            Si no recuerda su contrase&ntilde;a, use la opci&oacute;n de recuperaci&oacute;n disponible en la pantalla de ingreso.
        </p>
    @endif

    <p>Que tenga un excelente d&iacute;a.</p>
    <p>Atentamente: Semilla Digital</p>
</body>
</html>
