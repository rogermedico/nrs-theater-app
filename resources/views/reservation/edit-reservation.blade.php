@extends('main')

@section('main-content')
    <div class="offset-lg-2 col-lg-8 ">
        <h1 class="serif">{{__('edit reservation')}}</h1>
        <x-messages/>
        <x-errors/>
        <form class="needs-validation" novalidate method="post" action="{{route('reservation.update', $reservation)}}">
            @csrf
            @method('put')
            <div class="card mb-3">
                <div class="card-body d-flex flex-column">
                    <div class="text-center mb-3">
                        <h2 class="border border-primary rounded offset-md-4 col-md-4 py-3">stage</h2>
                    </div>
                    <div class="mb-3">
                        @for ($row = 1; $row <= env('THEATER_MAX_ROWS'); $row++)
                            <div class="d-flex flex-row justify-content-center align-content-center">
                                @for ($column = 1; $column <= env('THEATER_MAX_COLUMNS'); $column++)
                                    <span class="mx-2">
                                        <input
                                            id="seat{{$row . '-' . $column}}"
                                            @class([
                                                'form-check-input',
                                                'seat-radio',
                                                'taken' => in_array($row . '-' . $column, $occupiedSeats),
                                                'user' => in_array($row . '-' . $column, $userSeats),
                                                'empty' => !in_array($row . '-' . $column, array_merge($occupiedSeats,$userSeats))
                                            ])
                                            type="radio" name="newseat"
                                            value="{{$row . '-' . $column}}"
                                            {{in_array($row . '-' . $column, array_merge($occupiedSeats,$userSeats)) ? 'disabled' : ''}}
                                            {{$reservation->row === $row && $reservation->column === $column ? 'checked' : ''}}
                                        >
                                    </span>
                                @endfor
                            </div>
                        @endfor
                    </div>
                    <div class="d-flex flex-row justify-content-center">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input seat-radio seat-radio-legend" type="checkbox" id="seat-checkbox-empty-legend" disabled>
                            <label class="form-check-label seat-radio-legend-label" for="seat-checkbox-empty-legend">{{__('Empty')}}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input seat-radio seat-radio-legend empty" type="radio" id="seat-checkbox-selected-legend" disabled checked>
                            <label class="form-check-label seat-radio-legend-label" for="seat-checkbox-selected-legend">{{__('Selected')}}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input seat-radio seat-radio-legend user" type="radio" id="seat-checkbox-selected-legend" disabled checked>
                            <label class="form-check-label seat-radio-legend-label" for="seat-checkbox-selected-legend">{{__('My seats')}}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input seat-radio seat-radio-legend taken" type="radio" id="seat-checkbox-taken-legend" disabled checked>
                            <label class="form-check-label seat-radio-legend-label" for="seat-checkbox-taken-legend">{{__('Occupied')}}</label>
                        </div>
                    </div>
                    <div class="d-flex flex-row">
                        <div class="flex-grow-1">
                            <a class="btn btn-outline-danger" href="{{route('user.reservations.show', auth()->user())}}">{{ __('Go back')}}</a>
                        </div>
                        <div class="flex-grow-1 text-end">
                            <button type="submit" class="btn btn-primary">{{ __('Confirm changes')}}</button>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
@endsection
