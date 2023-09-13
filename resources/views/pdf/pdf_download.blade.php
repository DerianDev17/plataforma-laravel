<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Pdf Download</title>
</head>
<style>

@page { margin: 0px; }
body { margin: 0px; }
  @font-face {
    font-family: "Asmelina Harley";
    src: url("{{ public_path('AsmelinaHarley.ttf') }}") format('truetype');
    font-weight: normal;
    font-style: normal;
   

  }

  .name {
    font-family: "Asmelina Harley";
    font-size: 80px;
    position: absolute;
    top: 40%;
    left: 50%;
    transform: translate(-50%, -50%);
    /* border: 5px solid red; */
    width: 850px;
    text-align: center;
  }
  .fecha {
    font-size: 30px;
    position: absolute;
    top: 80%;
    left: 60%;
    transform: translate(-50%, -50%);
  }
  body {
    /* background-image: url("{{ public_path('CERTIFICADO.jpg') }}"); */
    background-image: url("{{ public_path('CERTIFICADO.png') }}");
    background-size: cover;
  }
</style>

<body>
<div class="name">{{ $nombre }} {{ $apellido }}</div>
  <!-- <img style="width: 100%;" src="{{ public_path('CERTIFICADO.jpg') }}" style="width: 200px; height: 200px"> -->
  <div class="fecha">{{$newDate}}</div>

</body>

</html>