<x-app-layout>
    @if (Auth::user()->diapago != 0)
    @if((intval(date("d")) > (Auth::user()->diapago - 5)) && (intval(date("d")) <= (Auth::user()->diapago)) )
        <button id="btnModal" data-toggle="modal" hidden data-target="#updateModal" class="btn btn-primary btn-sm">Edit</button>
        @else
        <button id="btnModal2" class="btn btn-primary btn-sm" hidden>Edit</button>
        @endif
        @else
        <button id="btnModal2" class="btn btn-primary btn-sm" hidden>Edit</button>
        @endif

        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
        </x-slot>
        @if (Auth::user()->student_group_id != 3)
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-ui.alert color="indigo" message="LE RECORDAMOS QUE LA FECHA DE PAGO ES DEL 01 AL {{ Auth::user()->diapago() }} DE CADA MES" showbutton="0" />
        </div>
        @endif

        @if (!Auth::user()->email_verified_at)
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-ui.alert color="red" message="Su dirección de correo electrónico aún no ha sido verificada, por favor, verificarla dando clic en el botón." showbutton="1" />
        </div>
        @endif

        @if (!Auth::user()->cuestionario_resuelto && false)
        <x-ui.encuesta />
        @endif

        <div class="pb-12 pt-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <x-jet-welcome />
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="background-color: transparent; border: 0px">
                    <img src="/storage/img/Recurso.png">
                </div>
            </div>
        </div>
</x-app-layout>