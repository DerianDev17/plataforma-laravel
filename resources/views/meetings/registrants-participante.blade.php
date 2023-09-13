<x-landing.layout>
    <div class="container">
        <div class="row">
            <form action="{{ route('post.register_participants') }}" method="POST">
                @csrf
                <div class="form-group">
                    <div class="relative">
                        <select name="group_id" class="...">
                            @foreach($student_groups as $group)
                            <option value="{{$group->id}}">{{$group->code}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-default">Submit</button>
            </form>
        </div>
    </div>
    </x-landing-layout>