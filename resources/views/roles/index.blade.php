<x-landing.layout>
    <div class="container">
        <a href="roles/create" class="btn btn-success">Crear Nuevo Rol</a>

        <div class="row">
            <table class="table table-bordered">
                <thead>
                    <td>Id</td>
                    <td>Nombre del Rol</td>
                    <td>Label</td>
                    <td>Acciones</td>
                </thead>
                <tbody>
                    @foreach ($mis_roles as $rol)
                    <tr>
                        <td>{{$rol->id}}</td>
                        <td>{{$rol->name}}</td>
                        <td>{{$rol->label}}</td>
                        <td>
                            <a href="/roles/{{$rol->id}}/show" class="btn btn-info">Ver</a>
                            <a href="/roles/{{$rol->id}}/edit" class="btn btn-warning">Edit</a>
                            <a onclick="return confirm('Are you sure?')" href="/roles/{{$rol->id}}" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </x-landing-layout>