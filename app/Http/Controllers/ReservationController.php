<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(){
        return Reservation::where('session_id', 1)
            ->where('row', 1)
            ->where('column', 1)
            ->count();
    }
}
