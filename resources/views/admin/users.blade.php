@extends('main')

@section('main-content')
    <div class="offset-lg-2 col-lg-8 ">
        <h1 class="serif">{{__('manage users')}}</h1>
        <x-messages/>
        <x-errors/>
        <ul class="list-group">
            @foreach($users as $user)
                <li class="list-group-item d-flex flex-row">
                    <div class="flex-grow-1 align-self-center">
                        {{$user->name}}
                        {{$user->surname}}
                    </div>
                    <div class="flex-grow-1 d-flex flex-row justify-content-end">
                        <a class="btn btn-outline-success me-3" href="{{route('user.edit', $user)}}">{{__('Edit profile')}}</a>
                        <a class="btn btn-outline-success me-3" href="{{route('user.reservations.show', $user)}}">{{__('Edit reservations')}}</a>
                        <span>
                            <form action="{{ route('user.destroy', $user) }}" method="post">
                                @csrf
                                @method('delete')
                                <button class="btn btn-danger {{ $user->isAdmin() ? 'disabled' : ''  }}">
                                    {{__('Delete')}}
                                </button>
                            </form>
                        </span>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
@endsection

