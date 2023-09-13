<x-landing.layout>
    <div class="container">
        <div class="row">
            <form action="/users-roles" method="POST">
                @csrf
                <div class="col-sm-6">
                    <label for="sel2">Habilidad:</label>
                    <select multiple class="form-control" id="sel2" name="user">
                    @foreach ($users as $user)
                        <option value="{{$user->id}}">{{$user->name}}</option>
                    @endforeach
                    </select>
                </div>
                <div class="col-sm-6">
                    <label for="sel2">Rol:</label>
                    <select multiple class="form-control" id="sel2" name="rol">
                    @foreach ($roles as $rol)
                        <option value="{{$rol->id}}">{{$rol->name}}</option>
                    @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-default">Submit</button>
            </form>
        </div>
    </div>
    </x-landing-layout>