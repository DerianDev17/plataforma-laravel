<x-landing.layout>
    <div class="container">
        <div class="row">
            <form action="{{ route('post.register_participants') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Correo:</label>
                    <input class="form-control" id="email" name="email">
                    <label for="email">Nombre:</label>
                    <input class="form-control" id="name" name="name">
                    <label for="email">Id zoom:</label>
                    <input class="form-control" id="meeting_id" name="meeting_id">
                </div>
                <button type="submit" class="btn btn-default">Submit</button>
            </form>
        </div>
    </div>
</x-landing-layout>
