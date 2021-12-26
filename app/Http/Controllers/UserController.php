<?php

namespace App\Http\Controllers;

use App\Http\Requests\user\LoginUserRequest;
use App\Http\Requests\user\StoreUserRequest;
use App\Http\Requests\user\UpdateUserPasswordRequest;
use App\Http\Requests\user\UpdateUserProfileRequest;
use App\Models\Session;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * Constructor, apply middleware auth to specific routes
     *
     */
    public function __construct()
    {
        $this->middleware('auth')->only(['index', 'edit', 'update', 'destroy', 'logout']);
        $this->middleware('guest')->only(['create', 'store']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        if (Gate::denies('index', auth()->user()))
        {
            return redirect()->route('user.edit', auth()->user());
        }

        return view('admin.users', [
            'users' => User::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        return view('users.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->validated());
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route('reservation.create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(User $user)
    {
        if (Gate::denies('edit', $user))
        {
            return redirect()->route('user.edit', auth()->user());
        }
        return view('users.profile', [
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateUserProfileRequest $request, User $user)
    {
        if (Gate::denies('updateProfile', $user))
        {
            return redirect()->route('reservation.create');
        }
        $user->update($request->validated());
        return redirect()->back()->with('message', __('Profile updated'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(UpdateUserPasswordRequest $request, User $user)
    {
        if (Gate::denies('updatePassword', $user))
        {
            return redirect()->route('reservation.create');
        }
        $user->update([
            'password' => $request->validated()['password']
        ]);
        return redirect()->back()->with('message', __('Password updated'));
    }

    /**
     *  Show reservations
     */
    public function showReservations(User $user)
    {
        if (Gate::denies('showReservations', $user))
        {
            return redirect()->route('user.reservations.show', auth()->user());
        }

        $reservations = [];
        foreach($user->reservations as $reservation)
        {
            $session = Session::find($reservation->session_id);
            $reservations[$session->name][Carbon::parse($session->date)->format('d/m/Y H:i')][] = [
                'id' => $reservation->id,
                'row' => $reservation->row,
                'column' => $reservation->column
            ];
        }

        return view('users.reservations', compact('reservations','user'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, User $user)
    {
        if (Gate::denies('delete', $user))
        {
            return redirect()->route('reservation.create');
        }

        /** user deleting his own account */
        if(auth()->user() === $user)
        {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            $user->delete();
            return redirect()->route('reservation.create');
        }

        $user->delete();
        return back()->with('message', __('User deleted'));


    }

    /**
     * Show the form to login users.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function loginShow()
    {
        return view('users.login');
    }

    /**
     * Logs in the user if the credentials are correct
     *
     * @param LoginUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(LoginUserRequest $request)
    {
        if (Auth::attempt($request->validated())) {
            $request->session()->regenerate();
            return redirect()->route('reservation.create');
        }

        return back()->withErrors([
            'email' => __('The provided credentials do not match our records.'),
        ]);
    }


    /**
     * Logs out the logged in user.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('reservation.create');
    }
}
