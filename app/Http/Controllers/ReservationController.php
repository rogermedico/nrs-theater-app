<?php

namespace App\Http\Controllers;

use App\Http\Requests\request\CreateFirstStepReservationRequest;
use App\Http\Requests\request\CreateSecondStepReservationRequest;
use App\Models\Reservation;
use App\Models\Session;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        return view('reservation.create', [
            'sessions' => Session::all(),
            'createReservationFirstStepInfo' => session('createReservationFirstStepInfo')
        ]);
    }

    /**
     * Show the second part of the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function createSecondStep(CreateFirstStepReservationRequest $request)
    {
        session(['createReservationFirstStepInfo' => $request->validated()]);

        $occupiedSeats = array_map(function ($seat) {
            return $seat['row'] . '-' . $seat['column'];
        },
            Session::find($request->validated()['session'])->reservations()->select(['row','column'])->get()->toArray()
        );
        return view('reservation.create-second-step', [
            'occupiedSeats' => $occupiedSeats
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function store(CreateSecondStepReservationRequest $request)
    {
        $seats = $request->validated()['seats'];
        $createReservationFirstStepInfo = $request->session()->pull('createReservationFirstStepInfo');

        if ($createReservationFirstStepInfo)
        {
            if (!auth()->user())
            {
                $user = User::create([
                    'name' => $createReservationFirstStepInfo['name'],
                    'surname' => $createReservationFirstStepInfo['surname'],
                    'email' => $createReservationFirstStepInfo['email'],
                    'password' => $createReservationFirstStepInfo['password']
                ]);

                Auth::attempt([
                    'email' => $user->email,
                    'password' => $createReservationFirstStepInfo['password']
                ]);
            } else {
                $user = auth()->user();
            }

            $session = Session::find($createReservationFirstStepInfo['session']);

            foreach($seats as $seat)
            {
                $rowColumn = explode('-', $seat);
                $reservation = Reservation::create([
                    'user_id' => $user->id,
                    'session_id' => $createReservationFirstStepInfo['session'],
                    'row' => $rowColumn[0],
                    'column' => $rowColumn[1]
                ]);

                Log::channel('reservations')
                    ->info($user->name
                        . ' '
                        . $user->surname
                        . ' (id='
                        . $user->id
                        . ') reserved a seat in row '
                        . $reservation->row
                        . ' and column '
                        . $reservation->column
                        . ' for the "'
                        . $session->name
                        . '" theater play at '
                        . Carbon::parse($session->date)->format('d/m/Y H:i')
                    );
            }

            session(['message' => __('Reservation confirmed, check my reservations section to manage reservations')]);
        }
        return view('reservation.create', [
            'sessions' => Session::all()
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  User $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(Reservation $reservation)
    {
//        if (Gate::denies('show', $user))
//        {
//            return redirect()->route('reservation.show', auth()->user());
//        }
//        $reservations = $user->reservations();//Reservation::where('user_id', $user->id)->get()->toArray();
//        return view('reservation.my-reservations', [
//            'reservations' => $reservations
//        ]);
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Reservation $reservation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Reservation $reservation)
    {
        if (Gate::denies('delete', $reservation))
        {
            return redirect()->route('user.reservations.show');
        }

        $reservation->delete();
        return back()->with('message', __('Reservation deleted'));
    }
}
