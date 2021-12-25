@extends('main')

@section('main-content')
    <div class="offset-lg-2 col-lg-8 ">
        <h1 class="serif">{{__('my reservations')}}</h1>
        <x-messages/>
        <x-errors/>
            @foreach($reservations as $reservationName => $reservation)
                <section class="my-3">
                    <h2>{{$reservationName}}</h2>
                    <ul class="list-group">
                        @foreach($reservation as $seat)
                            <li class="list-group-item d-flex flex-row">
                                <div class="flex-grow-1 align-self-center">
                                    {{__('Row: ')}}
                                    {{$seat['row']}}
                                    {{__(' Column: ')}}
                                    {{$seat['column']}}
                                </div>
        {{--                        <div class="flex-grow-1 d-flex flex-row justify-content-end">--}}
        {{--                            <a class="btn btn-outline-success me-3" href="{{route('user.edit', $user)}}">{{__('Edit')}}</a>--}}
        {{--                            <span>--}}
        {{--                                <form action="{{ route('user.destroy', $user) }}" method="post">--}}
        {{--                                    @csrf--}}
        {{--                                    @method('delete')--}}
        {{--                                    <button class="btn btn-danger {{ $user->isAdmin() ? 'disabled' : ''  }}">--}}
        {{--                                        {{__('Delete')}}--}}
        {{--                                    </button>--}}
        {{--                                </form>--}}
        {{--                            </span>--}}
        {{--                        </div>--}}
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endforeach
        </div>
    </div>
@endsection
