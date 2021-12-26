@extends('main')

@section('main-content')
    <div class="offset-lg-2 col-lg-8 ">
        <h1>{{__('my reservations')}}</h1>
        <x-messages/>
        <x-errors/>
        @foreach($reservations as $reservationName => $sessions)
            <section class="my-3">
                <h2>{{$reservationName}}</h2>
                @foreach($sessions as $sessionDatetime => $seats)
                    <p class="mt-3">
                        {{__('You have ')}}
                        {{count($seats)}}
                        {{__('tickets for the play at ')}}
                        {{$sessionDatetime}}</p>
                    <ul class="list-group">
                        @foreach($seats as $seat)
                            <li class="list-group-item d-flex flex-row">
                                <div class="flex-grow-1 align-self-center">
                                    {{__('Ticket at row: ')}}
                                    {{$seat['row']}}
                                    {{__(' and column: ')}}
                                    {{$seat['column']}}
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
    </div>
@endsection
