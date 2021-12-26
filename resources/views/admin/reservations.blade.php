@extends('main')

@section('main-content')
    <div class="offset-lg-2 col-lg-8 ">
        <h1 class="serif">{{__('manage reservations')}}</h1>
        <x-messages/>
        <x-errors/>
        <ul class="list-group">
            @foreach($reservations as $reservationName => $sessions)
                <section class="my-3">
                    <h2>{{$reservationName}}</h2>
                    @foreach($sessions as $sessionDatetime => $seats)
                        <p class="mt-3">
                            {{__('There are ')}}
                            {{count($seats)}}
                            {{__('tickets for the play at ')}}
                            {{$sessionDatetime}}</p>
                        <ul class="list-group">
                            @foreach($seats as $seat)
                                <li class="list-group-item d-flex flex-row">
                                    <div class="flex-grow-1 align-self-center">
                                        {{__('Seat ')}}
                                        {{$seat['row'] . '-' . $seat['column']}}
                                        {{__(' owned by ')}}
                                        {{$seat['user']->name}} {{$seat['user']->surname}}
                                    </div>
                                    <div class="flex-grow-1 d-flex flex-row justify-content-end">
                                        <a class="btn btn-outline-success me-3" href="{{route('reservation.edit', $seat['id'])}}">{{__('Edit')}}</a>
                                        <span>
                                        <form action="{{ route('reservation.destroy', $seat['id']) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button class="btn btn-danger">
                                                {{__('Delete')}}
                                            </button>
                                        </form>
                                    </span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endforeach
                </section>
            @endforeach
{{--            @foreach($reservations as $reservation)--}}
{{--                <li class="list-group-item d-flex flex-row">--}}
{{--                    <div class="flex-grow-1 align-self-center">--}}
{{--                        {{$user->name}}--}}
{{--                        {{$user->surname}}--}}
{{--                    </div>--}}
{{--                    <div class="flex-grow-1 d-flex flex-row justify-content-end">--}}
{{--                        <a class="btn btn-outline-success me-3" href="{{route('user.edit', $user)}}">{{__('Edit profile')}}</a>--}}
{{--                        <a class="btn btn-outline-success me-3" href="{{route('user.reservations.show', $user)}}">{{__('Edit reservations')}}</a>--}}
{{--                        <span>--}}
{{--                            <form action="{{ route('user.destroy', $user) }}" method="post">--}}
{{--                                @csrf--}}
{{--                                @method('delete')--}}
{{--                                <button class="btn btn-danger {{ $user->isAdmin() ? 'disabled' : ''  }}">--}}
{{--                                    {{__('Delete')}}--}}
{{--                                </button>--}}
{{--                            </form>--}}
{{--                        </span>--}}
{{--                    </div>--}}
{{--                </li>--}}
{{--            @endforeach--}}
        </ul>
    </div>
@endsection
