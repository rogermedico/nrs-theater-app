@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <ul class="list-group list-group-flush mb-0">
            @foreach ($errors->all() as $error)
                <li class="list-group-item list-group-item-danger">{{$error}}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
