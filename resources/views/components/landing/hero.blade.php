<div id="myCarousel" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
    <li data-target="#myCarousel" data-slide-to="1"></li>
    <li data-target="#myCarousel" data-slide-to="2"></li>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner">
    <div class="item active">
      <img src="{{ Storage::url('img/slider1-1200x700.jpg') }}" alt="Los Angeles" style="width:100%;">
      <div class="carousel-caption divcar">
        <h1 class="fontcar">ASEGURA <span class="navcolor">TU INGRESO</span> A LA U</h1>
        <!-- <p>LA is always so much fun!</p> -->
      </div>
    </div>

    <div class="item">
      <img src="{{ Storage::url('img/slider2-1200x700.jpg') }}" alt="Chicago" style="width:100%;">
      <div class="carousel-caption divcar">
        <h1 class="fontcar">INGRESA A UNIVERSIDADES <span class="navcolor">PÚBLICAS, PRIVADAS</span> Y ESCUELAS MILITARES</h1>
        <!-- <p>LA is always so much fun!</p> -->
      </div>
    </div>

    <div class="item">
      <img src="{{ Storage::url('img/slider3-1200x700.jpg') }}" alt="New york" style="width:100%;">
      <div class="carousel-caption divcar">
        <h1 class="fontcar">SÉ UN <span class="navcolor">PROFESIONAL</span> DE ÉXITO</h1>
        <!-- <p>LA is always so much fun!</p> -->
      </div>
    </div>
  </div>

  <!-- Left and right controls -->
  <a class="left carousel-control" href="#myCarousel" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#myCarousel" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
<!-- Codigo del chatbot-->
  <script type="text/javascript">
    var vsid = "kc22227ac5ae748";
    (function() { 
      var vsjs = document.createElement('script'); vsjs.type = 'text/javascript'; vsjs.async = true; vsjs.setAttribute('defer', 'defer');
      vsjs.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'www.leadchatbot.com/vsa/chat.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(vsjs, s);
    })();
  </script>

<!-- poner javascripts dentro de push('javascripts') -->
@push('javascripts')

@endpush