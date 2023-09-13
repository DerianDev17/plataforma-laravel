<x-landing.layout>
    <div class="container">
        <div class="row">
            <form action="/abilities" method="POST">
            @csrf
                <div class="form-group">
                    <label for="ability">Nombre:</label>
                    <input class="form-control" id="ability" name="nombre">
                </div>
                <div class="form-group">
                    <label for="label">Lavel:</label>
                    <input class="form-control" id="label" name="label">
                </div>
                <button type="submit" class="btn btn-default">Submit</button>
            </form>
        </div>
    </div>
    </x-landing-layout>