<!DOCTYPE html>

<html>

<head>

    <title>ItsolutionStuff.com</title>

</head>

<body>

    <h1>{{ $details['title'] }}</h1>

    <!-- <p>{{ $details['body'] }}</p> -->

    <img src="{{ URL::to('/').Storage::url('img/header_mail.jpeg') }}" alt="Paris" style="width:50%">


    <p>Reciba un cordial saludo de parte del equipo de Eus3.</p>
    <p>
        Le informamos que se le ha generado un nombre de usuario y una contraseña para que pueda acceder
        a la página web, deberá acceder con los siguientes datos:
    </p>

    <p>
        <strong>Nombre de usuario:</strong> {{$details['user']->username}} <br>
        <strong>Contraseña:</strong> {{$details['user']->username}} <br>
    </p>

    <p>
       Le solicitamos que al momento de ingresar cambie su contraseña a una mas segura, este cambio lo puede hacer
       desde la sección del perfil de usario.
    </p>

    <p>Que tenga un excelente día.</p>


    <p>Att: Eus3</p>

</body>

</html>