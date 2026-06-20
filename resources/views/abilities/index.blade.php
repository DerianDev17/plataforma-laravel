<x-app-layout>
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
                            <form style="display:inline" method="POST" action="/abilities/{{$ability->id}}" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </x-app-layout>
