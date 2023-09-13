<x-landing.layout>
    <div class="container">
        <div class="row">
            <form action="/roles/{{$role->id}}" method="POST">
            @csrf
            @method('PUT')
                <div class="form-group">
                    <label for="role">Nombre:</label>
                    <input class="form-control" id="role" name="nombre" value="{{$role->name}}">
                </div>
                <div class="form-group">
                    <label for="label">Lavel:</label>
                    <input class="form-control" id="label" name="label" value="{{$role->label}}">
                </div>
                <button type="submit" class="btn btn-default">Submit</button>
            </form>
        </div>
    </div>
    </x-landing-layout>