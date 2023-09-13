<div class="flex flex-col">
  <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
      <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
        @if ($user->status === 1)
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Hora de inicio
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Asunto
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Duración
              </th>
              <th scope="col" class="relative px-6 py-3">
                <span class="sr-only">Edit</span>
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($meetings as $meeting)
            <tr>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{\Carbon\Carbon::parse($meeting['start_time'])}}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{$meeting['topic']}}</div>
                <div class="text-sm text-gray-500">
                  @isset($meeting['agenda']){{$meeting['agenda']}}@endisset
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                {{$meeting['duration']}} minutos
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <a href="{{$meeting['join_url']}}" class="text-indigo-600 hover:text-indigo-900">Unirse</a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>

        @else
        <p>Lo sentimos, se le ha restringido el acceso a las sesiones por falta de pago.</p>
        @endif
      </div>
    </div>
  </div>
</div>


<table class="border-collapse w-full">
    <thead>
        <tr>
            <th class="font-bold uppercase tabfont text-white border border-black hidden lg:table-cell">Horario</th>
            <th class="font-bold uppercase tabfont text-white border border-black hidden lg:table-cell">Lunes</th>
            <th class="font-bold uppercase tabfont text-white border border-black hidden lg:table-cell">Martes</th>
            <th class="font-bold uppercase tabfont text-white border border-black hidden lg:table-cell">Miércoles</th>
            <th class="font-bold uppercase tabfont text-white border border-black hidden lg:table-cell">Jueves</th>
            <th class="font-bold uppercase tabfont text-white border border-black hidden lg:table-cell">Viernes</th>
            <th class="font-bold uppercase tabfont text-white border border-black hidden lg:table-cell">Horario</th>
            <th class="font-bold uppercase tabfont text-white border border-black hidden lg:table-cell">Sábado</th>
        </tr>
    </thead>
    <tbody>
        <tr class="bg-white lg:hover:bg-gray-100 flex lg:table-row flex-row lg:flex-row flex-wrap lg:flex-no-wrap mb-10 lg:mb-0">
            <td class="p-3 font-bold uppercase tabfont text-white border border-black hidden lg:table-cell text-center">
                19:00
            </td>
            <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b block lg:table-cell relative lg:static border-black">
                Ciencias Naturales
            </td>
            <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b block lg:table-cell relative lg:static border-black">
                Ciencias Sociales
            </td>
            <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b block lg:table-cell relative lg:static border-black">
                Lengua y Literatura
            </td>
            <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b text-center block lg:table-cell relative lg:static border-black">
                Orintación Vocacional
            </td>
            <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b text-center block lg:table-cell relative lg:static border-black">
                Matemática
            </td>
            <td class="p-3 font-bold uppercase tabfont text-white border border-black hidden lg:table-cell text-center">
                13:00
            </td>
            <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b text-center block lg:table-cell relative lg:static border-black">
                Ciencias Naturales
            </td>
            <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b text-center block lg:table-cell relative lg:static border-black">
            <a href="#" class="text-blue-800 hover:text-blue-600 underline">Zoom</a>
            </td>
        </tr>
        <tr class="bg-white lg:hover:bg-gray-100 flex lg:table-row flex-row lg:flex-row flex-wrap lg:flex-no-wrap mb-10 lg:mb-0">
            <td class="p-3 font-bold uppercase tabfont text-white border border-black hidden lg:table-cell text-center">
                19:00
            </td>
            <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b block lg:table-cell relative lg:static border-black">
                Ciencias Naturales
            </td>
            <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b block lg:table-cell relative lg:static border-black">
                Ciencias Sociales
            </td>
            <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b block lg:table-cell relative lg:static border-black">
                Lengua y Literatura
            </td>
            <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b text-center block lg:table-cell relative lg:static border-black">
                Orintación Vocacional
            </td>
            <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b text-center block lg:table-cell relative lg:static border-black">
                Matemática
            </td>
            <td class="p-3 font-bold uppercase tabfont text-white border border-black hidden lg:table-cell text-center">
                13:00
            </td>
            <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b text-center block lg:table-cell relative lg:static border-black">
                Ciencias Naturales
            </td>
            <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b text-center block lg:table-cell relative lg:static border-black">
            <a href="#" class="text-blue-800 hover:text-blue-600 underline">Zoom</a>
            </td>
        </tr><tr class="bg-white lg:hover:bg-gray-100 flex lg:table-row flex-row lg:flex-row flex-wrap lg:flex-no-wrap mb-10 lg:mb-0">
            <td class="p-3 font-bold uppercase tabfont text-white border border-black hidden lg:table-cell text-center">
                19:00
            </td>
            <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b block lg:table-cell relative lg:static border-black">
                Ciencias Naturales
            </td>
            <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b block lg:table-cell relative lg:static border-black">
                Ciencias Sociales
            </td>
            <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b block lg:table-cell relative lg:static border-black">
                Lengua y Literatura
            </td>
            <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b text-center block lg:table-cell relative lg:static border-black">
                Orintación Vocacional
            </td>
            <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b text-center block lg:table-cell relative lg:static border-black">
                Matemática
            </td>
            <td class="p-3 font-bold uppercase tabfont text-white border border-black hidden lg:table-cell text-center">
                13:00
            </td>
            <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b text-center block lg:table-cell relative lg:static border-black">
                Ciencias Naturales
            </td>
            <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b text-center block lg:table-cell relative lg:static border-black">
            <a href="#" class="text-blue-800 hover:text-blue-600 underline">Zoom</a>
            </td>
        </tr><tr class="bg-white lg:hover:bg-gray-100 flex lg:table-row flex-row lg:flex-row flex-wrap lg:flex-no-wrap mb-10 lg:mb-0">
            <td class="p-3 font-bold uppercase tabfont text-white border border-black hidden lg:table-cell text-center">
                19:00
            </td>
            <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b block lg:table-cell relative lg:static border-black">
                Ciencias Naturales
            </td>
            <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b block lg:table-cell relative lg:static border-black">
                Ciencias Sociales
            </td>
            <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b block lg:table-cell relative lg:static border-black">
                Lengua y Literatura
            </td>
            <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b text-center block lg:table-cell relative lg:static border-black">
                Orintación Vocacional
            </td>
            <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b text-center block lg:table-cell relative lg:static border-black">
                Matemática
            </td>
            <td class="p-3 font-bold uppercase tabfont text-white border border-black hidden lg:table-cell text-center">
                13:00
            </td>
            <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b text-center block lg:table-cell relative lg:static border-black">
                Ciencias Naturales
            </td>
            <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b text-center block lg:table-cell relative lg:static border-black">
            <a href="#" class="text-blue-800 hover:text-blue-600 underline">Zoom</a>
            </td>
        </tr><tr class="bg-white lg:hover:bg-gray-100 flex lg:table-row flex-row lg:flex-row flex-wrap lg:flex-no-wrap mb-10 lg:mb-0">
            <td class="p-3 font-bold uppercase tabfont text-white border border-black hidden lg:table-cell text-center">
                19:00
            </td>
            <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b block lg:table-cell relative lg:static border-black">
                Ciencias Naturales
            </td>
            <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b block lg:table-cell relative lg:static border-black">
                Ciencias Sociales
            </td>
            <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b block lg:table-cell relative lg:static border-black">
                Lengua y Literatura
            </td>
            <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b text-center block lg:table-cell relative lg:static border-black">
                Orintación Vocacional
            </td>
            <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b text-center block lg:table-cell relative lg:static border-black">
                Matemática
            </td>
            <td class="p-3 font-bold uppercase tabfont text-white border border-black hidden lg:table-cell text-center">
                13:00
            </td>
            <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b text-center block lg:table-cell relative lg:static border-black">
                Ciencias Naturales
            </td>
            <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b text-center block lg:table-cell relative lg:static border-black">
            <a href="#" class="text-blue-800 hover:text-blue-600 underline">Zoom</a>
            </td>
        </tr>

    </tbody>
</table>
<br>
