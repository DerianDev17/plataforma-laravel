<x-landing.layout>
    <div class="container">
        <div class="row">
            <div class="form-group">
                <label for="role">Nombre:</label>
                <span class="form-control">{{$role->name}}</span>
            </div>
            <div class="form-group">
                <label for="label">Lavel:</label>
                <span class="form-control">{{$role->label}}</span>
            </div>
        </div>
    </div>
    </x-landing-layout>