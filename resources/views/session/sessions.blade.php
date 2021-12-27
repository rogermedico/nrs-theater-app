@extends('main')

@section('main-content')
    <div class="offset-lg-2 col-lg-8 ">
        <h1 class="serif">{{__('manage sessions')}}</h1>
        <x-messages/>
        <x-errors/>
        <section class="my-3">
            <h2>{{__('session list')}}</h2>
            <ul class="list-group">
                @foreach($sessions as $session)
                    <li class="list-group-item d-flex flex-row">
                        <div class="flex-grow-1 align-self-center">
                            {{$session->name}}
                            {{__(' (')}}{{\Carbon\Carbon::parse($session->date)->format('d/m/Y H:i')}}{{__(')')}}
                        </div>
                        <div class="flex-grow-1 d-flex flex-row justify-content-end">
                            <a class="btn btn-outline-success me-3" href="{{route('session.edit', $session)}}">{{__('Edit')}}</a>
                            <span>
                                <form action="{{ route('session.destroy', $session) }}" method="post">
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
        </section>
        <section class="my-3">
            <h2>{{__('create session')}}</h2>
            <form class="needs-validation" novalidate method="post" action="{{route('session.store')}}">
                @csrf
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="name">{{ __('Name')}}</label>
                            <input
                                type="text"
                                class="form-control"
                                id="name"
                                placeholder="{{ __('Hamlet')}}"
                                name="name"
                                required
                            >
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="date">{{ __('Date')}}</label>
                            <input
                                type="datetime-local"
                                class="form-control date-input"
                                id="date"
                                name="date"
                                required
                            >
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="ticket_price">{{ __('Price')}}</label>
                            <input
                                type="number"
                                class="form-control"
                                id="ticket_price"
                                placeholder="{{__('100')}}"
                                name="ticket_price"
                            >
                        </div>
                        <div class="d-flex flex-row mb-3">
                            <div class="flex-grow-1 me-3 text-end">
                                <button type="submit" class="btn btn-primary">{{ __('Create')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>
@endsection

