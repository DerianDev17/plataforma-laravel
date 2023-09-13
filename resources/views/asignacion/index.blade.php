<x-landing.layout>
    <a href="roles/create" class="btn btn-success">Crear Nuevo Rol</a>
    <div class="row">
        <div class="col-sm-6">
            <table class="table table-bordered">
                <thead>
                    <td>Id</td>
                    <td>Nombre</td>
                    <td>Apellido</td>
                    <td>Roles</td>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>{{$user->id}}</td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->last_name}}</td>
                        <td>@foreach ($user->roles as $a)<span class="label label-success"> {{$a->name}}</span> @endforeach</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <a href="users-roles/create" class="btn btn-success">Asignar nuevo rol</a>
    </x-landing-layout>