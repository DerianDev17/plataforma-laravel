<div>
  <div class="bg-white">
    <nav class="tabs flex flex-col sm:flex-row">
      <button data-target="panel-1" class="tab active2 text-gray-600 py-4 px-6 block hover:text-blue-500 focus:outline-none text-blue-500 border-b-2 font-medium border-blue-500">
        Módulo 1
      </button>
      <button data-target="panel-2" class="tab ext-gray-600 py-4 px-6 block hover:text-blue-500 focus:outline-none">
        Módulo 2
      </button>
      <button data-target="panel-3" class="tab text-gray-600 py-4 px-6 {{$n_modules ? 'hidden' : 'block'}} hover:text-blue-500 focus:outline-none">
        Módulo 3
      </button>
      <button data-target="panel-4" class="tab text-gray-600 py-4 px-6 {{$n_modules ? 'hidden' : 'block'}} hover:text-blue-500 focus:outline-none">
        Módulo 4
      </button>
      <button data-target="panel-5" class="tab text-gray-600 py-4 px-6 {{$n_modules ? 'hidden' : 'block'}} hover:text-blue-500 focus:outline-none">
        Módulo 5
      </button>
    </nav>
  </div>

  <div id="panels">

    @php
    $counter = 1;
    @endphp

    @foreach ($drives as $i => $modulo)
    <div class="panel-{{$counter}} tab-content1 py-5 px-5">
      <div class="container mx-auto mt-5 grid grid-cols-1 gap-8 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($modulo as $drive)
        <div>

          @livewire('components.u-i.card', [
          'titulo' => $drive['materia'],
          'imgurl' => $drive['img_url'],
          'links' => $drive['links'],
          'colorurl' => $drive['card_color'],
          ])
          {{--
          @livewire('components.u-i.card-single-class.card-single-class', [
          'titulo' => $drive['materia'],
          'imgurl' => $drive['img_url'],
          'driveurl' => $drive['link'],
          'colorurl' => 'bg-blue-200',
          ])
          --}}
        </div>
        @endforeach
      </div>
    </div>
    @php
    $counter++;
    @endphp
    @endforeach

  </div>

</div>

@push('javascripts')
<script>
  const tabs = document.querySelectorAll(".tabs");
  const tab = document.querySelectorAll(".tab");
  const panel = document.querySelectorAll(".tab-content1");

  function onTabClick(event) {

    // deactivate existing active tabs and panel

    for (let i = 0; i < tab.length; i++) {
      tab[i].classList.remove("active2");
      tab[i].classList.remove("border-b-2");
      tab[i].classList.remove("border-blue-500");
    }

    for (let i = 0; i < panel.length; i++) {
      panel[i].classList.remove("active2");
      tab[i].classList.remove("border-b-2");
      tab[i].classList.remove("border-blue-500");
    }

    // activate new tabs and panel
    event.target.classList.add('active2');
    event.target.classList.add('border-b-2');
    event.target.classList.add('border-blue-500');
    let classString = event.target.getAttribute('data-target');
    console.log('classString :>> ', classString);
    document.getElementById('panels').getElementsByClassName(classString)[0].classList.add("active2");
  }

  for (let i = 0; i < tab.length; i++) {
    tab[i].addEventListener('click', onTabClick, false);
  }

  // default tab
  panel[0].classList.add('active2');
</script>

@endpush

@push('estilos')
<style>
</style>
@endpush