<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNrsTheater" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNrsTheater">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('reservation.create')}}">{{__('Make Reservation')}}</a>
                </li>
                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="#">My Reservations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user.auth.profile.show')}}">{{__('Profile')}}</a>
                    </li>
                    @if(auth()->user()->isAdmin())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Admin
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#">Manage users</a></li>
                                <li><a class="dropdown-item" href="#">Manage Reservations</a></li>
                            </ul>
                        </li>
                    @endif
                @endauth
            </ul>

        </div>
        <div class="d-flex">
            @auth
                <span class="navbar-brand d-none d-md-inline">
                    {{auth()->user()->name}} {{auth()->user()->surname}} ({{auth()->user()->email}})
                </span>
                <span class="navbar-brand d-md-none">
                    {{auth()->user()->name}} {{auth()->user()->surname}}
                </span>
                <a class="btn btn-outline-danger" href="{{route('user.auth.logout.make')}}">Logout</a>
            @endauth
            @guest
                <a class="btn btn-outline-success me-3" href="{{route('user.auth.register.show')}}">Register</a>
                <a class="btn btn-success" href="{{route('user.auth.login.show')}}">Login</a>
            @endguest

        </div>
    </div>
</nav>
