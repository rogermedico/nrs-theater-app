<?php

namespace App\Http\Controllers;

use App\Http\Requests\user\LoginUserRequest;
use App\Http\Requests\user\StoreUserRequest;
use App\Http\Requests\user\UpdateUserPasswordRequest;
use App\Http\Requests\user\UpdateUserProfileRequest;
use App\Models\Session;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Constructor, apply middleware auth to specific routes
     */
    public function __construct()
    {
        $this->middleware('auth')->only([
            'index',
            'edit',
            'update',
            'destroy',
            'logout',
        ]);

        $this->middleware('guest')->only([
            'create',
            'store',
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|RedirectResponse
     */
    public function index()
    {
        if (Gate::denies('index', auth()->user())) {
            return redirect()->route('user.edit', auth()->user());
        }

        return view('admin.users', [
            'users' => User::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('users.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreUserRequest  $request
     * @return RedirectResponse
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = User::create($request->validated());
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('reservation.create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User $user
     * @return Application|Factory|View|RedirectResponse
     */
    public function edit(User $user)
    {
        if (Gate::denies('edit', $user)) {
            return redirect()->route('user.edit', auth()->user());
        }

        return view('users.profile', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateUserProfileRequest  $request
     * @param  User $user
     * @return RedirectResponse
     */
    public function update(UpdateUserProfileRequest $request, User $user): RedirectResponse
    {
        if (Gate::denies('updateProfile', $user)) {
            return redirect()->route('reservation.create');
        }

        $user->update($request->validated());

        return redirect()->back()->with('message', __('Profile updated'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserPasswordRequest  $request
     * @param User $user
     * @return RedirectResponse
     */
    public function updatePassword(UpdateUserPasswordRequest $request, User $user): RedirectResponse
    {
        if (Gate::denies('updatePassword', $user)) {
            return redirect()->route('reservation.create');
        }

        if (isset($request->validated()['password_old'])) {
            if (!Hash::check($request->validated()['password_old'], $user->password)) {
                return redirect()->back()->withErrors(['password_old' => __('Incorrect old password')]);
            };
        }

        $user->update([
            'password' => $request->validated()['password'],
        ]);

        return redirect()->back()->with('message', __('Password updated'));
    }

    /**
     * Show reservations
     *
     * @param User $user
     * @return Application|Factory|View|RedirectResponse
     */
    public function showReservations(User $user)
    {
        if (Gate::denies('showReservations', $user)) {
            return redirect()->route('user.reservations.show', auth()->user());
        }

        $reservations = [];
        foreach ($user->reservations as $reservation) {
            $session = Session::find($reservation->session_id);
            $reservations[$session->name][Carbon::parse($session->date)->format('d/m/Y H:i')][] = [
                'id' => $reservation->id,
                'row' => $reservation->row,
                'column' => $reservation->column,
            ];
        }

        return view('users.reservations', compact('reservations', 'user'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        if (Gate::denies('delete', $user)) {
            return redirect()->route('reservation.create');
        }

        /**
         * User deleting his own account
        */
        if (auth()->user() === $user) {
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
     * @return Application|Factory|View
     */
    public function loginShow()
    {
        return view('users.login');
    }

    /**
     * Logs in the user if the credentials are correct
     *
     * @param LoginUserRequest $request
     * @return RedirectResponse
     */
    public function login(LoginUserRequest $request): RedirectResponse
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
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('reservation.create');
    }
}
