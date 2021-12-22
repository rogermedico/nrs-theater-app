@extends('main')

@section('main-content')
    <div>
        <h1 class="serif">{{__('users')}}</h1>
            <div class="offset-lg-2 col-lg-8 p-0">

                <ul class="list-group">
                    @foreach($users as $user)
                    <li class="list-group-item">{{$user->name}} {{$user->surname}} ({{$user->email}})</li>

                    @endforeach
                </ul>

            </div>
    </div>
@endsection
