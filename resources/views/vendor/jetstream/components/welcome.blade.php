<div class="p-6 sm:px-20 bg-white border-b border-gray-200 grid grid-cols-6 gap-4">
  <div class="col-span-6 md:col-span-2">
    <img class="" src="/storage/img/eus-logo.png" />
  </div>
  <div class="col-span-6 md:col-span-4">
    <div class="mt-8 text-2xl">
      Bienvenido a EUS3
    </div>

    <div class="mt-6 text-gray-500 text-2xl">
      El mejor programa de capacitación donde
      ingresarás a Universidades Públicas, Privadas o Escuelas
      Militares.
      Recuerda que tienes acceso a todas las clases de forma
      ilimitada.
      Conviértete en un profesional de ÉXITO.
    </div>
  </div>
</div>

<div class="p-6 sm:px-20 bg-white border-b border-gray-200 grid grid-cols-6 gap-4">
  <div class="col-span-6 md:col-span-6" style="text-align: -webkit-center;">
    <div class="teachers-slider-container" style="text-align: -webkit-center;">
      <div>
        <!-- <div class="slider-container"> -->
        <!-- <iframe loading="lazy" class="responsive-iframe" src="https://www.youtube.com/embed/M0HlfTOvD0A" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> -->
        <div data-vidcode="5AePv_9a9E4" class="yt_player" id="player"></div>
        <!-- </div> -->
      </div>
      <div>
        <div data-vidcode="JKTaKMPNnxg" class="yt_player" id="player1"></div>

        <!-- <div class="slider-container"> -->
        <!-- <iframe loading="lazy" class="responsive-iframe" src="https://www.youtube.com/embed/iplqOvYnxio" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> -->
        <!-- </div> -->
      </div>
      <div>
        <div data-vidcode="kzxPl55gyCo" class="yt_player" id="player2"></div>

        <!-- <div class="slider-container"> -->
        <!-- <iframe loading="lazy" class="responsive-iframe" src="https://www.youtube.com/embed/kg8Tie9UeJw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> -->
        <!-- </div> -->
      </div>
      <div>
        <div data-vidcode="iaZz_K_t0BM" class="yt_player" id="player3"></div>

        <!-- <div class="slider-container"> -->
        <!-- <iframe loading="lazy" class="responsive-iframe" src="https://www.youtube.com/embed/-gB7QkKxj14" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> -->
        <!-- </div> -->
      </div>
      <div>
        <div data-vidcode="UBcGWPdqRlQ" class="yt_player" id="player4"></div>

        <!-- <div class="slider-container"> -->
        <!-- <iframe loading="lazy" class="responsive-iframe" src="https://www.youtube.com/embed/VsaIq1KGIro" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> -->
        <!-- </div> -->
      </div>
      <div>
        <!-- <div data-vidcode="UwaWgcPFVu0" class="yt_player" id="player5"></div> -->

        <!-- <div class="slider-container"> -->
        <!-- <iframe loading="lazy" class="responsive-iframe" src="https://www.youtube.com/embed/UwaWgcPFVu0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> -->
        <!-- </div> -->
      </div>
    </div>
  </div>
</div>

@php
use Illuminate\Support\Facades\Auth;
$user = Auth::user();
@endphp

@if (!$user->adeuda() || $user->hasRole('superadmin'))
<div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2">

  <div class="p-6">
    <div class="flex items-center">
      <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-400">
        <path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
        <path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
      </svg>
      <div class="ml-4 text-lg text-gray-600 leading-7 font-semibold"><a href="{{ route('dashboard-meetings') }}">Clases en vivo</a></div>
    </div>

    <div class="ml-12">
      <div class="mt-2 text-sm text-gray-500">
        Accede a las clases en vivo, reuniones por medio de Zoom que nos permitiran guiarte en tus estudios, en esta
        nueva modalidad virtual.
      </div>
      <a href="{{ route('dashboard-meetings') }}">
        <div class="mt-3 flex items-center text-sm font-semibold text-indigo-700">
          <div>Ir a las reuniones</div>

          <div class="ml-1 text-indigo-500">
            <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
              <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
          </div>
        </div>
      </a>
    </div>
  </div>

  @if (false)
  <div class="p-6 border-t border-gray-200 md:border-t-0 md:border-l">
    <div class="flex items-center">
      <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-400">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
      </svg>
      <div class="ml-4 text-lg text-gray-600 leading-7 font-semibold"><a href="{{ route('course_programm') }}">Biblioteca Virtual</a></div>
    </div>

    <div class="ml-12">
      <div class="mt-2 text-sm text-gray-500">
        Registramos las clases dictadas para que puedas repasar el contenido del curso las veces que lo necesites.
      </div>
      @if($user->student_group_id != 3)
      <a href="{{ route('course_programm') }}">
        <div class="mt-3 flex items-center text-sm font-semibold text-indigo-700">
          <div>Explorar biblioteca virtual</div>

          <div class="ml-1 text-indigo-500">
            <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
              <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
          </div>
        </div>
      </a>
      @else
      <p class="text-red-600 italic pt-3">Próximamente</p>
      @endif
    </div>
  </div>
  @endif

  <div class="p-6 border-t border-gray-200">
    <div class="flex items-center">
      <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-400">
        <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
      </svg>
      <div class="ml-4 text-lg text-gray-600 leading-7 font-semibold"><a href="{{ route('material_digital') }}">Material digital</a></div>
    </div>

    <div class="ml-12">
      <div class="mt-2 text-sm text-gray-500">
        Eus3 te facilita una gran cantidad de recursos que cubren cada aspecto de las temáticas evaluadas en el EAES. Recomendamos
        que uses estos materiales para reforzar las clases.
      </div>
      <a href="{{ route('material_digital') }}">
        <div class="mt-3 flex items-center text-sm font-semibold text-indigo-700">
          <div>Explorar recursos</div>
          <div class="ml-1 text-indigo-500">
            <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
              <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
          </div>
        </div>
      </a>
      <!-- <p class="text-red-600 italic pt-3">Próximamente</p> -->
    </div>
  </div>

  <div class="p-6 border-t border-gray-200 md:border-l">
    <div class="flex items-center">
      <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-400">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
      </svg>
      <div class="ml-4 text-lg text-gray-600 leading-7 font-semibold"><a href="https://transformar.ec/simulador/practicar" target="_blank">Simulador</a></div>
    </div>

    <div class="ml-12">
      <div class="mt-2 text-sm text-gray-500">
        Ponemos a tu disposición el simulador del EAES con preguntas preseleccionadas para que puedas incrementar la probabilidad de un mayor puntaje.
      </div>
      <a href="https://transformar.ec/simulador/practicar" target="_blank">
        <div class="mt-3 flex items-center text-sm font-semibold text-indigo-700">
          <div>Practicar</div>

          <div class="ml-1 text-indigo-500">
            <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
              <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
          </div>
        </div>
      </a>
      <!-- <p class="text-red-600 italic pt-3">Próximamente</p> -->
    </div>

  </div>
</div>
@else
<div class="alert">
  <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
  Estimado/a estudiante.<br>
  Su cuenta se encuentra bloqueada por falta de pago.<br>
  <br>
  Por favor realice el pago de forma inmediata para acceder nuevamente.<br>
  <br>
  Pueden realizar el pago a las siguientes cuentas:<br>
  <br>
  <strong>Banco: </strong>Pichincha<br>
  <strong>Tipo de Cuenta: </strong>Corriente<br>
  <strong>Número de cuenta: </strong> 2100234142<br>
  <!-- <strong>Número de cuenta: </strong> 6132071800<br> -->
  <!-- <br>
          <strong>Banco:</strong> Pacífico<br>
          <strong>Tipo de Cuenta: </strong>Ahorros<br>
          <strong>Número de cuenta: </strong>1040212963<br>
          <br>
          <strong>Banco:</strong> Bolivariano<br>
          <strong>Tipo de Cuenta: </strong>Ahorros<br>
          <strong>Número de cuenta: </strong>1621086357<br>
          <br> -->
  <strong>Nombre: </strong>Eus3 Preuniversitario<br>
  <!-- <strong>Cédula: </strong>1717715773<br> -->
  <strong>Ruc: </strong>1793110533001<br>
  <!-- <strong>Correo de confirmación: </strong>eus3pre@gfeval.com<br> -->
  <strong>Correo: </strong>eus3pre@gfeval.com<br>
</div>
<div class="mnsj">
  <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
  Adjuntar el comprobante al 0963277997 o comuníquese al 025130777.<br>
  <strong> Estamos para ayudarle</strong><br>
  <strong>Servicio y Atención al Alumno</strong>
</div>
@endif

@if (false)
{{$user->exam_month}}
@endif

@push('javascripts')
<script>
  // 2. This code loads the IFrame Player API code asynchronously.
  var tag = document.createElement('script');

  tag.src = "https://www.youtube.com/iframe_api";
  var firstScriptTag = document.getElementsByTagName('script')[0];
  firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

  // 3. This function creates an <iframe> (and YouTube player)
  //    after the API code downloads.
  var player;
  var players = [];

  function onYouTubeIframeAPIReady() {
    jQuery('div[id^="player"]').each(function(i, elem) {
      player = new YT.Player(elem.id, {
        height: '390',
        width: '640',
        videoId: $(elem).data('vidcode'),
        playerVars: {
          'playsinline': 1
        },
        events: {
          'onReady': onPlayerReady,
          'onStateChange': onPlayerStateChange
        }
      });
      players.push(player);
      // console.log($(elem).data('vidcode'))
    })
    // console.log(players)

  }

  // 4. The API will call this function when the video player is ready.
  function onPlayerReady(event) {
    //event.target.playVideo();
  }

  // 5. The API calls this function when the player's state changes.
  //    The function indicates that when playing a video (state=1),
  //    the player should play for six seconds and then stop.
  var done = false;

  function changeSlide() {
    $('.teachers-slider-container').slick('slickNext');
  }

  function onPlayerStateChange(event) {
    if (event.data == 0) {
      changeSlide();
      event.target.playVideo();
      done = true;
    }
    console.log(event.data)
  }

  function stopVideo() {
    player.stopVideo();
  }
</script>
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('.teachers-slider-container').slick({
      dots: true,
      infinite: true,
      speed: 300,
      slidesToShow: 1,
      adaptiveHeight: true,
      lazyLoad: "ondemand",
      // centerMode: true,
      // centerPadding: '50px',
      responsive: [{
          breakpoint: 1024,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
            infinite: true,
            dots: true
          }
        },
        {
          breakpoint: 600,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
            infinite: true,
            dots: true,
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
            infinite: true,
            dots: true
          }
        }
        // You can unslick at a given breakpoint now by adding:
        // settings: "unslick"
        // instead of a settings object
      ]
    });
  });
  $('.teachers-slider-container').on('beforeChange', function(event, slick, currentSlide, nextSlide) {
    console.log(nextSlide);
    players.forEach(function(ply) {
      ply.pauseVideo();
    })
    if (nextSlide == 0) {
      players[0].playVideo();
    }
    if (nextSlide == 1) {
      players[1].playVideo();
    }
    if (nextSlide == 2) {
      players[2].playVideo();
    }
    if (nextSlide == 3) {
      players[3].playVideo();
    }
    if (nextSlide == 4) {
      players[4].playVideo();
    }
    if (nextSlide == 5) {
      //players[5].playVideo();
    }
  });
</script>

@endpush
@push('estilos2')

<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />
<style>
  .slider-container {
    position: relative;
    overflow: hidden;
    width: 100%;
    padding-top: 56.25%;
    /* 16:9 Aspect Ratio (divide 9 by 16 = 0.5625) */
  }

  /* Then style the iframe to fit in the container div with full height and width */
  .responsive-iframe {
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    width: 100%;
    height: 100%;
  }

  .slick-dots {}
</style>
@endpush