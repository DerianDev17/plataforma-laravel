<x-landing.layout>
    <div class="container">
        <div class="row">
            <form action="/roles" method="POST">
            @csrf
                <div class="form-group">
                    <label for="role">Nombre:</label>
                    <input class="form-control" id="role" name="nombre">
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