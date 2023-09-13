<nav class="navbar navbar-default navbar-fixed-top barra_superior">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#myPage">
        <!-- <img style="width: 80px" src="{{ Storage::url('img/eus-logo2.png') }}" > -->
        <img style="width: 80px" src="{{ Storage::url('img/logo-horizontal.png') }}" >
      </a>
    </div>
    <div class="" id="myNavbar">
      <ul class="nav navbar-nav navbar-left">
        <li><a class="navtop" href="#about">NOSOTROS</a></li>
        <li><a class="navtop" href="#more-services">SERVICIOS</a></li>
        <li><a class="navtop" href="#contact">CONTACTO</a></li>
        @if (!Auth::check())
        <li><a class="navtop" href="/login">INICIAR SESIÓN</a></li>
        <!-- <li><a class="navtop" href="/register-student">REGISTRO</a></li> -->
        @endif
      </ul>
    </div>
    <div class="collapse navbar-collapse">
      <ul class="nav navbar-nav navbar-right">
        <li><a href="https://www.facebook.com/104550457674505?referrer=whatsapp" class="fa fa-facebook" target="_blank"></a></li>
        <li><a href="https://l.facebook.com/l.php?u=https%3A%2F%2Finstagram.com%2Feus3_preuniversitario%3Figshid%3D107f6gzlypyt8%26fbclid%3DIwAR3oAhHMcP9bl0o12Arbja62PD746YZYl-2nO7_oj2U2w-_6vTK-ftNaF8Q&h=AT21bm6OwMknahrPafXuSEeZBvzZJ4vi4ZLKURcViSSHmRCGrGCNNhYYN32xNJiV5pFZTYqEdK2phh0xtc1PX9Gw7dDP4go_MAQRhnlJhnILb4gFrWcmn35NeAd-j0OTcIF9sqsbfz0tcA" class="fa fa-instagram" target="_blank"></a></li>
        <li><a href="https://wa.me/593963277997" class="fa fa-whatsapp" target="_blank"></a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- poner javascripts dentro de push('javascripts') -->
@push('javascripts')
  <script>
    console.log('hola hola');
  </script>
@endpush
