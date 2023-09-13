<footer id="contact" class="text-center">
  <div class="containerpad" style=text-align:center;>
    <div class="row">
      <div class="col-sm-4">
        <span class="logo">
          <img style="width: 75%" src="{{ Storage::url('img/eus-logo.png') }}">
        </span>
      </div>
      <div class="col-sm-4 d-flex justify-content-center">
        <div class="mapouter">
          <div class="gmap_canvas">
            <iframe class="gmap_iframe" width="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width=600&amp;height=400&amp;hl=en&amp;q=av america y cristobal de acuña&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed">
            </iframe>
              <a href="https://embedgooglemap.xyz/">embed google map
              </a>
            </div>
          <style>.mapouter{position:relative;text-align:right;width:100%;height:400px;}.gmap_canvas {overflow:hidden;background:none!important;width:100%;height:400px;}.gmap_iframe {height:400px!important;}
          </style>
        </div>
      </div>
      <div class="col-sm-4 d-flex justify-content-center" style="padding-top: 5%;">
        <h4 class="nomargin">025 130 777</h4><br>
        <h4 class="nomargin"><a href="https://wa.me/593963277997" target="_blank">+593 96 327 7997</a></h4><br>
        <h4 class="nomargin"><a href="mailto:info@eus3pre.com" target="_blank">info@eus3pre.com</a></h4><br>
        <h4 class="nomargin">Av. America N29-106 y Acuña</h4><br>
        <h4 class="nomargin">Quito - Ecuador</h4><br>
      </div>
    </div>
  </div>
  <!-- Codigo del chatbot
  <script type="text/javascript">
    var vsid = "kc22227ac5ae748";
    (function() { 
      var vsjs = document.createElement('script'); vsjs.type = 'text/javascript'; vsjs.async = true; vsjs.setAttribute('defer', 'defer');
      vsjs.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'www.leadchatbot.com/vsa/chat.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(vsjs, s);
    })();
  </script>-->

</footer>

<!-- poner javascripts dentro de push('javascripts') -->
@push('javascripts')
<script>
  console.log('hola hola');
</script>
@endpush
