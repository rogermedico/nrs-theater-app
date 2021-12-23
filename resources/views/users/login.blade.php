@extends('main')

@section('main-content')
    <div>
        <h1 class="serif">{{__('login')}}</h1>
        <div class="offset-lg-2 col-lg-8 p-0">
            <x-errors/>
            <form class="needs-validation" novalidate method="POST" action="{{route('user.login')}}">
                @csrf
                <div class="card">
                    <div class="card-body">
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
                                required
                            >
                        </div>
                    </div>

                    <div class="d-flex flex-row mb-3">
                        <div class="flex-grow-1 ms-3">
                            <a class="btn btn-outline-danger" href="{{route('reservation.create')}}">{{ __('Cancel')}}</a>
                        </div>
                        <div class="flex-grow-1 me-3 text-end">
                            <button type="submit" class="btn btn-primary" value="Send">{{ __('Login')}}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

