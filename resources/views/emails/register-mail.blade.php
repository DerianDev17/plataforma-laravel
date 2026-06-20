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
    <p>
        Le informamos que se le ha generado un nombre de usuario y una contrase&ntilde;a para que pueda acceder
        a la p&aacute;gina web. Deber&aacute; acceder con los siguientes datos:
    </p>

    <p>
        <strong>Nombre de usuario:</strong> {{ $details['user']->username }} <br>
        <strong>Contrase&ntilde;a:</strong> {{ $details['user']->username }} <br>
    </p>

    <p>
        Le solicitamos que al momento de ingresar cambie su contrase&ntilde;a a una m&aacute;s segura. Este cambio lo puede hacer
        desde la secci&oacute;n del perfil de usuario.
    </p>

    <p>Que tenga un excelente d&iacute;a.</p>
    <p>Atentamente: Semilla Digital</p>
</body>
</html>
