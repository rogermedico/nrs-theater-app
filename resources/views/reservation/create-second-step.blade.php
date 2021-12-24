@extends('main')

@section('main-content')
    <div class="offset-lg-2 col-lg-8 ">
        <h1 class="serif">{{__('reservation')}}</h1>
        <x-messages/>
        <x-errors/>
        <form class="needs-validation" novalidate method="post" action="{{route('reservation.store')}}">
            @csrf
            <div class="card mb-3">
                <div class="card-body">
                    @for ($row = 1; $row <= env('THEATER_MAX_ROWS'); $row++)
                        <div>
                            @for ($column = 1; $column <= env('THEATER_MAX_COLUMNS'); $column++)
                                <span class="form-check form-check-inline">
                                    <input
                                        id="seat{{$row . '-' . $column}}"
                                        class="form-check-input"
                                        type="checkbox" name="seats[]"
                                        value="{{$row . '-' . $column}}"
                                        {{in_array($row . '-' . $column, $occupiedSeats) ? 'disabled checked' : ''}}
                                    >

                                    <label class="form-check-label" for="seat{{$row . '-' . $column}}">
                                        {{$row . '-' . $column}}
                                    </label>
                                </span>
                            @endfor
                        </div>
                    @endfor
                    <div class="d-flex flex-row">
                        <div class="flex-grow-1 ms-3">
                            <a class="btn btn-outline-danger" href="{{route('reservation.create')}}">{{ __('Go back')}}</a>
                        </div>
                        <div class="flex-grow-1 me-3 text-end">
                            <button type="submit" class="btn btn-primary">{{ __('Confirm reservation')}}</button>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
@endsection
