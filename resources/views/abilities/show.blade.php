<x-landing.layout>
    <div class="container">
        <div class="row">
            <div class="form-group">
                <label for="ability">Nombre:</label>
                <span class="form-control">{{$ability->name}}</span>
            </div>
            <div class="form-group">
                <label for="label">Lavel:</label>
                <span class="form-control">{{$ability->label}}</span>
            </div>
        </div>
    </div>
    </x-landing-layout>