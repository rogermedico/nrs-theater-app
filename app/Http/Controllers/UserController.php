<?php

namespace App\Http\Controllers;

use App\Http\Requests\user\LoginUserRequest;
use App\Http\Requests\user\RegisterUserRequest;
use App\Http\Requests\user\UpdateUserPasswordRequest;
use App\Http\Requests\user\UpdateUserProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(UpdateUserProfileRequest $request, User $user)
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

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $user->delete();

        return redirect()->route('reservation.create');

    }

    public function loginShow()
    {
        if (auth()->user())
        {
            return redirect()->route('reservation.create');
        }

        return view('users.login');
    }

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

    public function registerShow()
    {
        if (auth()->user())
        {
            return redirect()->route('reservation.create');
        }

        return view('users.register');
    }

    public function register(RegisterUserRequest $request)
    {
        $user = User::create($request->validated());
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route('reservation.create');

    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('reservation.create');
    }
}
