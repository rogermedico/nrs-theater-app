@if(session('message'))
    <div class='alert alert-success alert-dismissible fade show'>
        {{session('message')}}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
