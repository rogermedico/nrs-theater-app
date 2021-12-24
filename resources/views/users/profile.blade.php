@extends('main')

@section('main-content')
    <div class="offset-lg-2 col-lg-8 ">
        <h1 class="serif">{{__('profile')}}</h1>
        <x-messages/>
        <x-errors/>
        <section class="my-3">
            <h2>{{__('update personal data')}}</h2>
            <form class="needs-validation" novalidate method="post" action="{{route('user.update', $user)}}">
                @csrf
                @method('put')
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="name">{{ __('Name')}}</label>
                            <input
                                type="text"
                                class="form-control"
                                id="name"
                                placeholder="{{ __('John')}}"
                                name="name"
                                required
                                value="{{$user->name}}"
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
                                value="{{$user->surname}}"
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
                                value="{{$user->email}}"
                            >
                        </div>
                    </div>

                    <div class="me-3 mb-3 text-end">
                        <button type="submit" class="btn btn-primary">{{ __('Update user info')}}</button>
                    </div>
                </div>
            </form>
        </section>
        <section class="my-3">
            <h2>{{__('update password')}}</h2>
            <form class="needs-validation" novalidate method="post" action="{{route('user.update.password', $user)}}">
                @csrf
                @method('put')
                <div class="card">
                    <div class="card-body">
                        @if(!auth()->user()->isAdmin() || auth()->user()->id === $user->id)
                            <div class="mb-3">
                                <label class="form-label" for="password_old">{{ __('Old password')}}</label>
                                <input
                                    type="password"
                                    class="form-control"
                                    id="password_old"
                                    placeholder="{{__('old password')}}"
                                    name="password_old"
                                    minlength="8"
                                    required
                                >
                            </div>
                        @endif
                        <div class="mb-3">
                            <label class="form-label" for="password">{{ __('New password')}}</label>
                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                placeholder="{{__('new password')}}"
                                name="password"
                                minlength="8"
                                required
                            >
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="password_confirmation">{{ __('Confirm new password')}}</label>
                            <input
                                type="password"
                                class="form-control"
                                id="password_confirmation"
                                placeholder="{{__('new password')}}"
                                name="password_confirmation"
                                minlength="8"
                                required
                            >
                        </div>
                    </div>

                    <div class="me-3 mb-3 text-end">
                        <button type="submit" class="btn btn-primary">{{ __('Update password')}}</button>
                    </div>
                </div>
            </form>
        </section>
        @if(!auth()->user()->isAdmin())
            <section class="my-3">
                <h2>{{__('delete account')}}</h2>
                <form class="needs-validation" novalidate method="post" action="{{route('user.destroy', auth()->user())}}">
                    @csrf
                    @method('delete')
                    <div class="card">
                        <div class="card-body">
                            <div>
                                This action is not reversible, all your data and reservations will be deleted.
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-danger">{{ __('Delete account')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </section>
        @endif
    </div>
@endsection

