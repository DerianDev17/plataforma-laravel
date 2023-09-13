  <!-- This example requires Tailwind CSS v2.0+ -->
  <div class="bg-{{$color}}-500 mx-auto  px-1.5 md:px-4 sm:px-6 lg:px-8 sm:rounded-lg">
    <div class="max-w-7xl mx-auto py-3 px-3 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between flex-wrap">
        <div class="w-0 flex-1 flex items-center">
          <span class="flex p-2 rounded-lg bg-{{$color}}-600">
            <!-- Heroicon name: speakerphone -->
            <!-- <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
            </svg> -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </span>
          <p class="ml-3 font-medium text-white">
            <span class="md:hidden text-xs">
              {{ $message }}
            </span>
            <span class="hidden md:inline">
              {{ $message }}
            </span>
          </p>
        </div>
        @if ($showbutton == '1')
        <!-- <div class="order-3 mt-2 flex-shrink-0 w-full sm:order-2 sm:mt-0 sm:w-auto">
          <a href="#" class="flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-indigo-600 bg-white hover:bg-indigo-50">
            Verificar
          </a>
        </div> -->

        <form method="POST" action="{{ route('verification.send') }}">
          @csrf
          <div>
            <button type="submit" class="focus:outline-none text-{{$color}}-600 text-sm sm:py-2.5 py-1.5 md:px-5 px-1.5 rounded-md bg-white hover:bg-{{$color}}-50 hover:shadow-lg">
              Enviar correo de verificación.
            </button>
            <!-- <x-jet-button type="submit">
              {{ __('Resend Verification Email') }}
            </x-jet-button> -->
          </div>
        </form>
        @endif
        <div class="order-2 flex-shrink-0 sm:order-3 sm:ml-3">
          <button type="button" class="-mr-1 flex p-2 rounded-md hover:bg-{{$color}}-500 focus:outline-none focus:ring-2 focus:ring-white sm:-mr-2">
            <span class="sr-only">Dismiss</span>
            <!-- Heroicon name: x -->
            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>