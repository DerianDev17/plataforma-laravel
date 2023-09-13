<div>

    @if (session()->has('error') && $show_alert)
    <div class="container" id="alertbox">
        <div class="container bg-red-500 flex items-center text-white text-sm font-bold px-4 py-3 relative" role="alert">
            <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z" />
            </svg>
            <p>{{ session('error') }}.</p>

            <span wire:click="closeAlert()" class="absolute top-0 bottom-0 right-0 px-4 py-3 closealertbutton">
                <svg class="fill-current h-6 w-6 text-white" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <title>Close</title>
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                </svg>
            </span>
        </div>
    </div>
    @elseif (session()->has('ok') && $show_alert)
    <div class="container" id="alertbox">
        <div class="container bg-green-500 flex items-center text-white text-sm font-bold px-4 py-3 relative" role="alert">
            <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z" />
            </svg>
            <p>{{ session('ok') }}.</p>

            <span wire:click="closeAlert()" class="absolute top-0 bottom-0 right-0 px-4 py-3 closealertbutton">
                <svg class="fill-current h-6 w-6 text-white" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <title>Close</title>
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                </svg>
            </span>
        </div>
    </div>
    @endif
    <div class="mb-4 sm:ml-4 xl:mr-4">
        <div class="shadow-lg rounded-2xl bg-white dark:bg-gray-700 w-full">
            <!-- <div class="flex items-center p-4 justify-between">
            <p class="font-bold text-md text-black dark:text-white">
                Google
            </p>
            <button class="text-sm p-1 text-gray-400 border rounded border-gray-400 mr-4">
                <svg width="15" height="15" fill="currentColor" viewBox="0 0 20 20">
                    <g fill="none">
                        <path d="M17.222 8.685a1.5 1.5 0 0 1 0 2.628l-10 5.498A1.5 1.5 0 0 1 5 15.496V4.502a1.5 1.5 0 0 1 2.223-1.314l10 5.497z" fill="currentColor">
                        </path>
                    </g>
                </svg>
            </button>
        </div>
        <div class="py-2 px-4 bg-blue-100 dark:bg-gray-800 text-gray-600 border-l-4 border-blue-500 flex items-center justify-between">
            <p class="text-xs flex items-center dark:text-white">
                <svg width="20" height="20" fill="currentColor" class="text-blue-500 mr-2" viewBox="0 0 24 24">
                    <g fill="none">
                        <path d="M12 5a8.5 8.5 0 1 1 0 17a8.5 8.5 0 0 1 0-17zm0 3a.75.75 0 0 0-.743.648l-.007.102v4.5l.007.102a.75.75 0 0 0 1.486 0l.007-.102v-4.5l-.007-.102A.75.75 0 0 0 12 8zm7.17-2.877l.082.061l1.149 1a.75.75 0 0 1-.904 1.193l-.081-.061l-1.149-1a.75.75 0 0 1 .903-1.193zM14.25 2.5a.75.75 0 0 1 .102 1.493L14.25 4h-4.5a.75.75 0 0 1-.102-1.493L9.75 2.5h4.5z" fill="currentColor">
                        </path>
                    </g>
                </svg>
                Create wireframe
            </p>
            <div class="flex items-center">
                <span class="font-bold text-xs dark:text-gray-200 mr-2 ml-2 md:ml-4">
                    25 min 20s
                </span>
                <button class="text-sm p-1 text-gray-400 border rounded bg-blue-500 mr-4">
                    <svg width="17" height="17" fill="currentColor" viewBox="0 0 24 24" class="text-white">
                        <g fill="none">
                            <path d="M9 6a1 1 0 0 1 1 1v10a1 1 0 1 1-2 0V7a1 1 0 0 1 1-1zm6 0a1 1 0 0 1 1 1v10a1 1 0 1 1-2 0V7a1 1 0 0 1 1-1z" fill="currentColor">
                            </path>
                        </g>
                    </svg>
                </button>
            </div>
        </div> -->
            <div class="flex items-center p-4 justify-between border-b-2 border-gray-100">
                <p class="font-bold text-md text-black dark:text-white">
                    Sesiones del día
                </p>
            </div>

            @foreach ($today_sessions as $session)
            <div class="py-2 px-4 text-gray-600 flex items-center justify-between border-b-2 border-gray-100">
                <p class="text-xs flex items-center dark:text-white">
                    <svg width="20" height="20" fill="currentColor" class="mr-2" viewBox="0 0 24 24">
                        <g fill="none">
                            <path d="M12 5a8.5 8.5 0 1 1 0 17a8.5 8.5 0 0 1 0-17zm0 3a.75.75 0 0 0-.743.648l-.007.102v4.5l.007.102a.75.75 0 0 0 1.486 0l.007-.102v-4.5l-.007-.102A.75.75 0 0 0 12 8zm7.17-2.877l.082.061l1.149 1a.75.75 0 0 1-.904 1.193l-.081-.061l-1.149-1a.75.75 0 0 1 .903-1.193zM14.25 2.5a.75.75 0 0 1 .102 1.493L14.25 4h-4.5a.75.75 0 0 1-.102-1.493L9.75 2.5h4.5z" fill="currentColor">
                            </path>
                        </g>
                    </svg>

                    {{$session[1]}} ({{$session[0]}}) 
                </p>
                <div class="flex items-center">
                    <span class="text-xs text-gray-600 mr-2 ml-2 md:ml-4">
                        Registrar asistencia
                    </span>
                    <button {{$session['asistio'] ? 'disabled' : ''}} wire:click="registAttendance('{{$session[1]}}', '{{$session[0]}}')" class="text-sm p-1 text-gray-400 border rounded border-gray-400 mr-4">
                        <svg width="12" height="12" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="{{$session['asistio'] ? '#34A853' : '#d10100'}}">
                            <path fill-rule="evenodd" d="M9 3a1 1 0 012 0v5.5a.5.5 0 001 0V4a1 1 0 112 0v4.5a.5.5 0 001 0V6a1 1 0 112 0v5a7 7 0 11-14 0V9a1 1 0 012 0v2.5a.5.5 0 001 0V4a1 1 0 012 0v4.5a.5.5 0 001 0V3z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
            @endforeach

        </div>
    </div>

    <div class="mb-4 flex flex-col sm:ml-4 xl:mr-4">
        <div class="-my-2 overflow-x-auto ">
            <div class="py-2 align-middle inline-block min-w-full">
                <div class="shadow-lg overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    @if (!$user->adeuda())
                    <div style="overflow-x:auto;">
                        <table class="border-collapse w-full">
                            <thead>
                                <tr>
                                    <th class="font-bold uppercase tabfont text-white border border-black  table-cell">Horario</th>
                                    <th class="font-bold uppercase tabfont text-white border border-black  table-cell">Lunes</th>
                                    <th class="font-bold uppercase tabfont text-white border border-black  table-cell">Martes</th>
                                    <th class="font-bold uppercase tabfont text-white border border-black  table-cell">Miércoles</th>
                                    <th class="font-bold uppercase tabfont text-white border border-black  table-cell">Jueves</th>
                                    <th class="font-bold uppercase tabfont text-white border border-black  table-cell">Viernes</th>
                                    <th class="font-bold uppercase tabfont text-white border border-black  table-cell">Horario</th>
                                    <th class="font-bold uppercase tabfont text-white border border-black  table-cell">Sábado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($horario as $dia )
                                <tr class="bg-white lg:hover:bg-gray-100 lg:table-row flex-row lg:flex-row flex-wrap lg:flex-no-wrap mb-10 lg:mb-0">
                                    <td class="p-3 font-bold uppercase tabfont text-white border border-black   table-cell text-center">
                                        {{$dia[0]}}
                                    </td>
                                    <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b block table-cell relative lg:static border-black">
                                        <a class="horario-materia-link" href="{{ $zoom_link }}" target="_link">{{$dia[1]}}</a>
                                    </td>
                                    <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b block table-cell relative lg:static border-black">
                                        <a class="horario-materia-link" href="{{ $zoom_link }}" target="_link">{{$dia[2]}}</a>
                                    </td>
                                    <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b block table-cell relative lg:static border-black">
                                        <a class="horario-materia-link" href="{{ $zoom_link }}" target="_link">{{$dia[3]}}</a>
                                    </td>
                                    <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b text-center block table-cell relative lg:static border-black">
                                        <a class="horario-materia-link" href="{{ $zoom_link }}" target="_link">{{$dia[4]}}</a>
                                    </td>
                                    <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b text-center block table-cell relative lg:static border-black">
                                        <a class="horario-materia-link" href="{{ $zoom_link }}" target="_link">{{$dia[5]}}</a>
                                    </td>
                                    <td class="p-3 font-bold uppercase tabfont text-white border border-black   table-cell text-center">
                                        {{$dia[6]}}
                                    </td>
                                    <td class="w-full lg:w-auto p-3 text-gray-800 bg-gray-400 text-center border border-b text-center block table-cell relative lg:static border-black">
                                        <a class="horario-materia-link" href="{{ $zoom_link }}" target="_link">{{$dia[7]}}</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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
                </div>
            </div>
        </div>
    </div>
</div>
{{--
<!-- modal -->
<div class="main-modal fixed w-full h-100 inset-0 z-50 overflow-hidden flex justify-center items-center animated fadeIn faster" style="background: rgba(0,0,0,.7);">
    <div class="border border-teal-500 shadow-lg modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">
        <div class="modal-content py-4 text-left px-6">
            <!--Title-->
            <div class="flex justify-between items-center pb-3">
                <p class="text-2xl font-bold">Atención</p>
                <div class="modal-close cursor-pointer z-50">
                    <svg class="fill-current text-black" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                        <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z">
                        </path>
                    </svg>
                </div>
            </div>
            <!--Body-->
            <div class="my-5">
                <p>
                    Con el fin de levantar información sobre su interacción con las clases en vivo, le solicitaremos permiso para utilizar notificaciones en su navegador.
                </p>
            </div>
            <!--Footer-->
            <div class="flex justify-end pt-2">
                <!-- <button class="focus:outline-none modal-close px-4 bg-gray-400 p-3 rounded-lg text-black hover:bg-gray-300">Cancel</button> -->
                <button onclick="askPermission()" class="focus:outline-none px-4 bg-teal-500 p-3 ml-3 rounded-lg text-white hover:bg-teal-400">Confirmar</button>
            </div>
        </div>
    </div>
</div>
--}}


@push('javascripts')
<script>
    let askPermission; {
        {
            --

            jQuery(function() {
                askPermission = () => {
                    Notification.requestPermission().then(function(permission) {
                        // If the user accepts, let's create a notification
                        if (permission === "granted") {
                            // var notification = new Notification(mensaje);
                            const modal = document.querySelector('.main-modal');
                            modal.classList.remove('fadeIn');
                            modal.classList.add('fadeOut');
                            modal.style.display = 'none';
                        } else {
                            modalClose();
                        }
                    });
                }

                const modal = document.querySelector('.main-modal');
                const closeButton = document.querySelectorAll('.modal-close');

                const modalClose = () => {
                    modal.classList.remove('fadeIn');
                    modal.classList.add('fadeOut');
                    modal.style.display = 'none';
                }

                const openModal = () => {
                    modal.classList.remove('fadeOut');
                    modal.classList.add('fadeIn');
                    modal.style.display = 'flex';
                }

                for (let i = 0; i < closeButton.length; i++) {
                    const elements = closeButton[i];
                    elements.onclick = (e) => modalClose();
                    modal.style.display = 'none';
                    window.onclick = function(event) {
                        if (event.target == modal) modalClose();
                    }
                }

                if (!("Notification" in window)) {
                    alert("Este navegador no soporta notificaciones.");
                } else if (Notification.permission === "granted") {
                    modalClose();
                } else if (Notification.permission !== "denied") {
                    openModal()
                }
            });
            --
        }
    }
</script>

@endpush