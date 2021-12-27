@extends('main')

@section('main-content')
    <div class="offset-lg-2 col-lg-8 ">
        <h1 class="serif">{{__('edit session')}}</h1>
        <x-messages/>
        <x-errors/>
        <form class="needs-validation" novalidate method="post" action="{{route('session.update', $session)}}">
            @csrf
            @method('put')
            <div class="card mb-3">
                <div class="card-body">
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
                                    value="{{$session->name}}"
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
                                    value="{{$session->date}}"
                                >
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="price">{{ __('Price')}}</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    id="price"
                                    placeholder="{{__('100')}}"
                                    name="price"
                                    value="{{$session->ticket_price}}"
                                >
                            </div>
                            <div class="d-flex flex-row mb-3">
                                <div class="flex-grow-1 me-3 text-end">
                                    <button type="submit" class="btn btn-primary">{{ __('Update session')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
