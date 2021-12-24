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
                    @guest
                        <div class="mb-3">
                            <label class="form-label" for="name">{{ __('Name')}}</label>
                            <input
                                type="text"
                                class="form-control"
                                id="name"
                                placeholder="{{ __('John')}}"
                                name="name"
                                required
                            >
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="surname">{{ __('Surname')}}</label>
                            <input
                                type="text"
                                class="form-control"
                                id="surname"
                                placeholder="{{ __('Smith')}}"
                                name="surname"
                                required
                            >
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="email">{{ __('Email')}}</label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                placeholder="{{__('example@gmail.com')}}"
                                name="email"
                                pattern="^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)+$"
                                required
                            >
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="password">{{ __('Password')}}</label>
                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                placeholder="{{__('password')}}"
                                name="password"
                                minlength="8"
                                required
                            >
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="password_confirmation">{{ __('Confirm password')}}</label>
                            <input
                                type="password"
                                class="form-control"
                                id="password_confirmation"
                                placeholder="{{__('password')}}"
                                name="password_confirmation"
                                minlength="8"
                                required
                            >
                        </div>
                    @endguest
                    <div class="mb-3">
                        <label class="form-label" for="session">{{__('Session')}}</label>
                            <select class="form-select" name="session" id="session" required>
                                <option selected value="">{{__('Select Session')}}</option>
                                @foreach($sessions as $session)
                                    <option value="{{$session->id}}">
                                        {{$session->name . ' - ' . \Carbon\Carbon::parse($session->date)->format('d/m/Y H:i')}}
                                    </option>
                                @endforeach
                            </select>
                    </div>
{{--                @for ($row = 1; $row <= env('THEATER_MAX_ROWS'); $row++)--}}
{{--                    <div>--}}
{{--                            @for ($column = 1; $column <= env('THEATER_MAX_COLUMNS'); $column++)--}}
{{--                            <span class="form-check form-check-inline">--}}
{{--                                <input id="seat{{$row . '-' . $column}}" class="form-check-input" type="checkbox" name="seats[]" value="{{$row . '-' . $column}}">--}}
{{--                                <label class="form-check-label" for="seat{{$row . '-' . $column}}">--}}
{{--                                    {{$row . '-' . $column}}--}}
{{--                                </label>--}}
{{--                            </span>--}}
{{--                    @endfor--}}
{{--                    </div>--}}
{{--                @endfor--}}
                    <div class="d-flex flex-row">
{{--                    <div class="form-group text-center text-md-right">--}}
                        <div class="flex-grow-1 text-end">
                            <button type="submit" class="btn btn-primary " >{{ __('Next')}}</button>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
