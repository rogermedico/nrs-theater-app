@extends('main')

@section('main-content')
    <div>
        <h1 class="serif">{{__('reservation')}}</h1>
        @if(session('message'))
            <div class='alert alert-success'>
                {{session('message')}}
            </div>
        @else
            <div class="offset-lg-2 col-lg-8 p-0">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="needs-validation" novalidate method="POST" action="{{route('reservation.store')}}">

                    @csrf

                    <div class="form-group flex-md-grow-1 mr-md-3">
                        <label class="sr-only" for="name">{{ __('Name')}}</label>
                        <input
                            type="text"
                            class="form-control"
                            id="name"
                            placeholder="{{ __('John')}}"
                            name="name"
                            required
                        >
                    </div>
                    <div class="form-group flex-md-grow-1 mr-md-3">
                        <label class="sr-only" for="name">{{ __('Surname')}}</label>
                        <input
                            type="text"
                            class="form-control"
                            id="surname"
                            placeholder="{{ __('Smith')}}"
                            name="surname"
                            required
                        >
                    </div>
                    <div class="form-group flex-md-grow-1">
                        <label class="sr-only" for="email">{{ __('Email')}}</label>
                        <input
                            type="email"
                            class="email form-control"
                            id="email"
                            placeholder="{{__('example@gmail.com')}}"
                            name="email"
                            pattern="^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)+$"
                            required
                        >
                    </div>
                    <label for="session">{{__('Session')}}
                        <select class="form-group form-select" name="session" id="session" required>
                            <option selected value="">{{__('Select Session')}}</option>
                            @foreach($sessions as $session)
                                <option value="{{$session->id}}">
                                    {{$session->name . ' - ' . \Carbon\Carbon::parse($session->date)->format('d/m/Y H:i')}}
                                </option>
                            @endforeach
                        </select>
                    </label>
                    @for ($row = 1; $row <= env('THEATER_MAX_ROWS'); $row++)
                        <div>
                                @for ($column = 1; $column <= env('THEATER_MAX_COLUMNS'); $column++)
                                <span class="form-check form-check-inline">
                                    <input id="seat{{$row . '-' . $column}}" class="form-check-input" type="checkbox" name="seats[]" value="{{$row . '-' . $column}}">
                                    <label class="form-check-label" for="seat{{$row . '-' . $column}}">
                                        {{$row . '-' . $column}}
                                    </label>
                                </span>
                        @endfor
                        </div>
                    @endfor

                    <div class="form-group text-center text-md-right">
                        <button type="submit" class="btn btn-primary" value="Send">{{ __('next')}}</button>
                    </div>
                </form>
            </div>
        @endif
    </div>
@endsection
