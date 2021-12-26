<?php

namespace App\Http\Controllers;

use App\Http\Requests\reservation\CreateFirstStepReservationRequest;
use App\Http\Requests\reservation\CreateSecondStepReservationRequest;
use App\Http\Requests\reservation\UpdateReservationRequest;
use App\Models\Reservation;
use App\Models\Session;
use App\Models\User;
use Carbon\Carbon;
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
            Session::find($request->validated()['session'])->reservations()->select(['row', 'column'])->get()->toArray()
        );

        if(auth()->user()) {
            $userSeats = array_map(function ($seat) {
                return $seat['row'] . '-' . $seat['column'];
            },
                Reservation::where('session_id', $request->validated()['session'])
                ->where('user_id', auth()->user()->id)
                ->select(['row', 'column'])
                ->get()
                ->toArray()
            );

            $occupiedSeats = array_diff($occupiedSeats, $userSeats);
        }



        return view('reservation.create-second-step', [
            'occupiedSeats' => $occupiedSeats,
            'userSeats' => $userSeats ?? []
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Reservation $reservation)
    {
        if (Gate::denies('edit', $reservation))
        {
            return redirect()->route('user.reservations.show', auth()->user());
        }

        $occupiedSeats = array_map(function ($seat) {
            return $seat['row'] . '-' . $seat['column'];
        },
            Reservation::where('session_id',$reservation->session_id)
                ->where(function ($q) use ($reservation) {
                    $q->where('row', '!=', $reservation->row);
                    $q->orWhere('column', '!=', $reservation->column);

                })
                ->select(['row','column'])
                ->get()
                ->toArray()
        );

        $userSeats = array_map(function ($seat) {
            return $seat['row'] . '-' . $seat['column'];
        },
            Reservation::where('session_id', $reservation->session_id)
                ->where('user_id', $reservation->user_id)
                ->where(function ($q) use ($reservation) {
                    $q->where('row', '!=', $reservation->row);
                    $q->orWhere('column', '!=', $reservation->column);

                })
                ->select(['row', 'column'])
                ->get()
                ->toArray()
        );

        $occupiedSeats = array_diff($occupiedSeats, $userSeats);

        return view('reservation.edit-reservation', [
            'reservation' => $reservation,
            'occupiedSeats' => $occupiedSeats,
            'userSeats' => $userSeats
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        if (Gate::denies('update', $reservation))
        {
            return redirect()->route('user.reservations.show', auth()->user());
        }
        $rowColumn = explode('-', $request->validated()['newseat']);
        $reservation->update([
            'row' => $rowColumn[0],
            'column' => $rowColumn[1]
        ]);

        $reservations = [];
        foreach(User::find($reservation->user_id)->reservations as $reservation)
        {
            $session = Session::find($reservation->session_id);
            $reservations[$session->name][Carbon::parse($session->date)->format('d/m/Y H:i')][] = [
                'id' => $reservation->id,
                'row' => $reservation->row,
                'column' => $reservation->column
            ];
        }

        return view('users.my-reservations', compact('reservations'))->with('message', __('Reservation updated'));
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
            return redirect()->route('user.reservations.show', auth()->user());
        }

        $reservation->delete();
        return back()->with('message', __('Reservation deleted'));
    }
}
