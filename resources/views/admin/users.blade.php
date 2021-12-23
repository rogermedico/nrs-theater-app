@extends('main')

@section('main-content')
    <div>
        <h1 class="serif">{{__('users')}}</h1>
        <div class="offset-lg-2 col-lg-8 p-0">
            @if($errors->any())
                <div class="alert alert-danger alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <ul class="list-group">
                @foreach($users as $user)
                    <li class="list-group-item d-flex flex-row">
                        <div class="flex-grow-1 align-self-center">
                            {{$user->name}}
                            {{$user->surname}}
                        </div>
                        <div class="flex-grow-1 d-flex flex-row justify-content-end">
                            <a class="btn btn-outline-success me-3" href="{{route('user.edit', $user)}}">{{__('Edit')}}</a>
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
    </div>
@endsection

