<x-landing.layout>
    <div class="container">
        <div class="row">
            <form action="/abilities/{{$ability->id}}" method="POST">
            @csrf
            @method('PUT')
                <div class="form-group">
                    <label for="ability">Nombre:</label>
                    <input class="form-control" id="ability" name="nombre" value="{{$ability->name}}">
                </div>
                <div class="form-group">
                    <label for="label">Lavel:</label>
                    <input class="form-control" id="label" name="label" value="{{$ability->label}}">
                </div>
                <button type="submit" class="btn btn-default">Submit</button>
            </form>
        </div>
    </div>
    </x-landing-layout>