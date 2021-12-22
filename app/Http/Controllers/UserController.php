<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        return view('users.login', [
            'users' => User::all()
        ]);
    }

    /**
     * Login the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
//    public function login(User $user)
//    {
//        //
//    }

    /**
     * Logout the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function logout($id)
    {
        //
    }

}
