<div class="flex flex-col">
  <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
      <x-jet-input type="text" placeholder="Buscar" wire:model="searchTerm" />
      <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Nombre
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Carrera
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Estado
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Rol
              </th>
              <th scope="col" class="relative px-6 py-3">
                <span class="sr-only">Edit</span>
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($users as $u)

            <tr>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <img class="h-10 w-10 rounded-full" src="{{ URL::asset('storage/users/default.png') }}" alt="">
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">
                      {{$u['name']}} {{$u['last_name']}}

                    </div>
                    <div class="text-sm text-gray-500">
                      {{$u['email']}}
                    </div>
                    <div class="text-sm text-gray-500">
                      {{$u['cellphone']}}
                    </div>
                    <div class="text-sm text-gray-500">
                      {{$u['highschool']}}
                    </div>
                    <div class="text-sm text-gray-500">
                      {{$u['city']}}
                    </div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900"> {{$u['especialty']}}
                </div>
                <div class="text-sm text-gray-500"> {{$u['paralelo']}}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                @if ($u['status']===1)
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                  Activo
                </span>
                @else
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                  Inactivo
                </span>
                @endif
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                Estudiante
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <a href="{{ route('user.show', $u['id']) }}" class="text-black-600 visited:text-black-600">Ver</a>
                <a href="{{ route('profile.show') }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>
                @if ($u['status']===0)

                <a href="{{ route('dashboard-active', $u['id'])}}" class="text-green-600 visited:text-green-600">Activar</a>
                @else

                <a href="{{ route('dashboard-block', $u['id'])}}" class="text-blue-600 visited:text-purple-600">Desactivar</a>
                @endif

              </td>
            </tr>
            @endforeach
            <!-- More rows... -->
          </tbody>
        </table>

        {{ $users->links('components.ui.pagination',['is_livewire' => true])}}

        @if (false)
        <livewire:datatable model="App\Models\User" include="id, name, email" searchable="name, email" exportable />
        @endif


      </div>
    </div>
  </div>
</div>