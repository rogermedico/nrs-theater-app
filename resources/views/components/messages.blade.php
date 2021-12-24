@if(session('message'))
    <div class='alert alert-success alert-dismissible fade show'>
        {{\Illuminate\Support\Facades\Session::pull('message')}}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

@endif
