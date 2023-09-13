<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Transformar
    </h2>
</x-slot>
<div class="max-w-7xl font-sans">
    @if (session()->has('message'))
    <div id="alert" class="text-white px-6 py-4 border-0 rounded relative mb-4 bg-green-500">
        <span class="inline-block align-middle mr-8">
            {{ session('message') }}
        </span>
        <button class="absolute bg-transparent text-2xl font-semibold leading-none right-0 top-0 mt-4 mr-6 outline-none focus:outline-none" onclick="document.getElementById('alert').remove();">
            <span>×</span>
        </button>
    </div>
    @endif
    
    <!--livewire:course-programs.course-program-->
    
    <!-- nuevo -->
    <div class="flex flex-col p-2">
        
        <div class="text-xl font-extrabold">
            <p>A continuacion elige que materia deseas revisar:</p>
            <!-- select cursos -->
            <select class="cursor-pointer form-select px-8 py-2 rounded-full" wire:model="selected_course_id" wire:change="courseChange" name="" id="">
                @foreach($courses as $course_program)
                <option value="{{$course_program->id}}">{{$course_program->course_name}}</option>
                @endforeach
            </select>
            @can ('crud_course_programs')
            <!-- select paralelos -->
            <select class="cursor-pointer form-select pr-6 py-2 rounded-full" wire:model="selected_group_id" wire:change="studentGroupChange" name="" id="">
                @foreach($student_groups as $groups)
                <option value="{{$groups->id}}">{{$groups->code}}</option>
                @endforeach
            </select>
            @endcan
        </div> 
        <div  class="text-xl font-extrabold"><H1 style='text-align: center'>{{$selected_course->course_name}}</H1></div>
        <div class="py-2 px-4">
            <div>
                <h3>Temas:</h3>
            </div>
            <div class="flex flex-col py-2 px-4">
                @foreach ($selected_course->topics as $topic)
                <div class="my-2">
                    <div class="flex flex-row justify-between">
                        <div class="font-bold text-blue-700 flex flex-row">
                            <div>
                                @can ('crud_course_programs')
                                <x-jet-input class="mt-1 block w-full text-xl" id="toUpdateTopicTitle-{{$topic->id}}" type="text" value="{{$topic->topic_title}}" />
                                @else
                                <div class="mt-1 block w-full text-xl">{{$topic->topic_title}}</div>
                                @endcan
                            </div>
                            @can ('crud_course_programs')
                            <!-- boton actualizar -->
                            <button onclick="updateTopicJS({{$topic->id}})" class="flex items-center font-medium tracking-wide text-green-400 transition-colors duration-200 transform rounded-md hover:bg-green-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-green-100 dark:focus:bg-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                            </button>
                            @endcan
                        </div>
                        <div class="text-center self-center">
                            @can ('crud_course_programs')
                            <!-- boton borrar -->
                            <button onclick="confirm('¿Esta seguro de borrar el contenido?') || event.stopImmediatePropagation()" wire:click.prevent="deleteTopic({{$topic->id}})" class="flex items-center font-medium tracking-wide text-red-500 transition-colors duration-200 transform rounded-md hover:bg-red-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-red-100 dark:focus:bg-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            @endcan
                        </div>
                    </div>
                    <div class="px-4 py-5 border-b rounded-t sm:px-6">
                        <!-- <h3>Recursos</h3> -->
                        <div class="bg-white dark:bg-gray-800 overflow-hidden border-black-300 border-1 rounded-sm">
                            <ul id="resources_list-{{$topic->id}}" class="divide-y divide-gray-200" data-topicId="{{$topic->id}}">
                                @forelse ($topic->resources as $resource)
                                <li id="resource_item-{{$resource->id}}-list-{{$topic->id}}" class="" data-resource_id="{{$resource->id}}">
                                    <div class="block hover:bg-gray-50 dark:hover:bg-gray-900">
                                        <div class="px-4 py-4 sm:px-6">
                                            <div class="flex items-center justify-between">
                                                <div class="flex flex-row">
                                                    @can ('crud_course_programs')
                                                    <!-- drag -->
                                                    <span class="flex items-center font-medium tracking-wide cursor-move transition-colors duration-200 transform mr-3 handle-{{$topic->id}}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L5 6.414V12zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                                                        </svg>
                                                    </span>
                                                    @endcan
                                                    <p class="text-md text-gray-700 dark:text-white md:truncate">{{$resource->resource_title}}</p>
                                                </div>
                                                @can ('crud_course_programs')
                                                <div class="flex flex-row">
                                                    <!-- boton actualizar recurso -->
                                                    <button wire:click.prevent="prepareToEditResource({{$resource->id}})" class="flex items-center font-medium tracking-wide text-green-400 transition-colors duration-200 transform rounded-md hover:bg-text-green-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-text-green-100 dark:focus:bg-gray-700">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                        </svg>
                                                    </button>
                                                    <!-- boton borrar recurso -->
                                                    <button wire:click.prevent="deleteResource({{$resource->id}})" class="flex items-center font-medium tracking-wide text-red-500 transition-colors duration-200 transform rounded-md hover:bg-red-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-red-100 dark:focus:bg-gray-700">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                @endcan
                                            </div>
                                            <!--<p>Esta es una prueba para comprabar en que parte estoy</p>-->

                                            <div class="mt-2 sm:flex sm:justify-between">
                                                @if ($resource->resource_url)
                                                <a class="flex no-underline hover:underline text-indigo-600 text-md items-center" href="{{$resource->resource_url->url}}">
                                                    <img src="{{ Storage::url('img/zoom.png') }}" class="object-scale-down h-8 pr-1" alt="" role="presentation" aria-hidden="true">
                                                    {{$resource->resource_url->url}}
                                                </a>
                                                @elseif ($resource->resource_file)
                                                <div>
                                                    <a class="flex  no-underline hover:underline text-indigo-600 text-md items-center" wire:click.prevent="downloadResourceFile({{$resource->resource_file->id}})" href="">
                                                        <img src="{{ Storage::url('img/document.png') }}" class="object-scale-down h-8 pr-1" alt="" role="presentation" aria-hidden="true">
                                                        {{$resource->resource_title}}
                                                    </a>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @empty
                                <div class="text-sm italic ">No se han agregado recursos.</div>
                                @endforelse
                            </ul>
                        </div>
                        @can ('crud_course_programs')
                        <div class="bg-white text-right my-2">
                            <a wire:click.prevent="prepareToAddResource({{$topic->id}})" href="#" class="text-blue-600 hover:text-blue-900 text-sm">
                                <svg class="w-4 h-4 mr-1 inline-block" xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Agregar Recurso
                            </a>
                        </div>
                        @endcan
                    </div>
                </div>
                @endforeach

                @can ('crud_course_programs')
                <div class="grid grid-cols-6 gap-4 place-content-center bg-white my-2 ">
                    <div class="col-span-5">
                        <x-jet-input id="topic_title" type="text" class="m-1 block w-full" wire:model="new_topic_title" />
                        <!-- <x-jet-input-error for="topic_title" class="mt-2" /> -->
                    </div>
                    <div class="text-center self-center">
                        <a wire:click.prevent="addTopic" href="#" class="text-indigo-600 hover:text-indigo-900">
                            <svg class="w-4 h-4 mr-1 inline-block" xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Agregar Tema
                        </a>
                    </div>
                </div>
                @endcan
            </div>
        </div>
    </div>

    <!-- formulario creacion de recursos -->
    @if($isOpen)
    <x-ui.customised-modal>
        <x-slot name="content">
            <!-- <form wire:submit.prevent="createResource"> -->
            <form wire:submit.prevent="saveResource">
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <select wire:model="res_type" wire:change="resChange" name="" id="">
                            <option value="url">Link</option>
                            <option value="file">Archivo</option>
                        </select>
                    </div>
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <label for="titleInput" class="block text-gray-700 text-sm font-bold mb-2">Título:</label>
                            <input wire:model="resTitle" type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="title_form_resource" placeholder="Ingrese un Título">
                            @error('resTitle') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    @if($res_type == 'url')
                    <!-- url -->
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <label for="titleInput" class="block text-gray-700 text-sm font-bold mb-2">Link:</label>
                            <input wire:model="resUrl" type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="link_form_resource" placeholder="Ingrese una URL">
                            @error('resUrl') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    @elseif($res_type == 'file')
                    <!-- Archivo -->
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <label for="titleInput" class="block text-gray-700 text-sm font-bold mb-2">Archivo:</label>
                            <input type="file" wire:model="resourceFile" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <div wire:loading wire:target="resourceFile">Cargando...</div>
                            <x-jet-input-error for="resourceFile" class="mt-2" />
                        </div>
                    </div>
                    @endif
                </div>
                <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <span class="flex w-full sm:ml-3 sm:w-auto">
                        <button class="inline-flex bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Guardar</button>
                        <!-- <button wire:click.prevent="createResource" type="button" class="inline-flex bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Crear</button> -->
                    </span>
                    <span class="mt-3 flex w-full sm:mt-0 sm:w-auto">
                        <button wire:click="closeModal()" type="button" class="inline-flex bg-white hover:bg-gray-200 border border-gray-300 text-gray-500 font-bold py-2 px-4 rounded">Cancelar</button>
                    </span>
                </div>
            </form>
        </x-slot>
        </x-customised-modal>
        @endif

</div>

@push('estilos')
@endpush

@push('javascripts')
<!-- jsDelivr :: Sortable :: Latest (https://www.jsdelivr.com/package/npm/sortablejs) -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
    function updateTopicJS(topicId) {
        var title = jQuery('#toUpdateTopicTitle-' + topicId).val();
        // console.log(title);
        window.livewire.emit('updateTopic', {
            topicId,
            title,
        });
    }

    function updateResourceJS(topicId) {
        var title = jQuery('#toUpdateTopicTitle-' + topicId).val();
        console.log(title);
        window.livewire.emit('updateTopic', {
            topicId,
            title,
        });
    }

    function updateResourcesOrderJS(resourcesOrdering) {
        // console.log('hola :>> ', 'hola');
        window.livewire.emit('updateOrdering', {
            orders: resourcesOrdering,
        });
    }

    function setupDragAndDrop() {
        let resourceLists = document.querySelectorAll('ul[id^="resources_list-"]');

        resourceLists.forEach((resource) => {
            console.log('resource :>> ', resource);
            let listWithHandle = document.getElementById(resource.id);

            Sortable.create(listWithHandle, {
                handle: '.handle-' + resource.dataset.topicid,
                animation: 150,
                // Element dragging ended
                onEnd: function(evt) {
                    let orders = [];
                    // obtener el orden
                    for (let i = 0; i < evt.from.children.length; i++) {
                        console.log('evt.from.children[i] :>> ', evt.from.children[i]);
                        orders.push({
                            resourceId: evt.from.children[i].dataset.resource_id,
                            order: i + 1
                        });
                    }

                    updateResourcesOrderJS(orders);

                    // console.log('orders :>> ', orders);
                },
            });
        });
    }

    document.addEventListener('livewire:load', function() {
        setupDragAndDrop();
    })

    Livewire.on('selectHasChanged', () => {
        setupDragAndDrop();
    });

    // var resourcesUL = document.querySelector('.handle');
</script>
@endpush