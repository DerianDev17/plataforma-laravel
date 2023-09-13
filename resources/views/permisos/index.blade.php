<x-landing.layout>
    <a href="roles/create" class="btn btn-success">Crear Nuevo Rol</a>
    <a href="abilities/create" class="btn btn-success">Crear Nueva Habilidad</a>
    <div class="row">
        <div class="col-sm-6">
            <table class="table table-bordered">
                <thead>
                    <td>Id</td>
                    <td>Nombre del rol</td>
                    <td>label</td>
                    <td>Habilidades</td>
                </thead>
                <tbody>
                    @foreach ($roles as $rol)
                    <tr>
                        <td>{{$rol->id}}</td>
                        <td>{{$rol->name}}</td>
                        <td>{{$rol->label}}</td>
                        <td>@foreach ($rol->abilities as $a)<span class="label label-success"> {{$a->name}}</span> @endforeach</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <a href="roles-permisos/create" class="btn btn-success">Asignar nueva habilidad</a>
    </x-landing-layout>