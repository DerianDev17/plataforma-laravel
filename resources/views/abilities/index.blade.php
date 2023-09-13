<x-landing.layout>
    <div class="container">
        <a href="abilities/create" class="btn btn-success">Crear Nueva Habilidad</a>
        <div class="row">
            <table class="table table-bordered">
                <thead>
                    <td>Id</td>
                    <td>Nombre de la Habilidad</td>
                    <td>Label</td>
                    <td>Acciones</td>
                </thead>
                <tbody>
                    @foreach ($mis_abilities as $ability)
                    <tr>
                        <td>{{$ability->id}}</td>
                        <td>{{$ability->name}}</td>
                        <td>{{$ability->label}}</td>
                        <td>
                            <a href="/abilities/{{$ability->id}}/show" class="btn btn-info">Ver</a>
                            <a href="/abilities/{{$ability->id}}/edit" class="btn btn-warning">Edit</a>
                            <a onclick="return confirm('Are you sure?')" href="/abilities/{{$ability->id}}" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </x-landing-layout>