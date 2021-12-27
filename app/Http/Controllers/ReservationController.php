<?php

namespace App\Http\Controllers;

use App\Http\Requests\reservation\ProcessFirstStepReservationRequest;
use App\Http\Requests\reservation\ProcessSecondStepReservationRequest;
use App\Http\Requests\reservation\UpdateReservationRequest;
use App\Models\Reservation;
use App\Models\Session;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
{
    /**
     * Constructor, apply middleware auth to specific routes
     *
     */
    public function __construct()
    {
        $this->middleware('auth')->only([
            'index',
            'edit',
            'update',
            'destroy',
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
            return redirect()->route('user.reservations.show', auth()->user());
        }

        $reservations = [];
        foreach (Reservation::orderBy('row')->orderBy('column')->get() as $reservation) {
            $session = Session::find($reservation['session_id']);
            $user = User::find($reservation->user_id);
            $reservations[$session->name][Carbon::parse($session->date)->format('d/m/Y H:i')][] = [
                'id' => $reservation->id,
                'user' => $user,
                'row' => $reservation->row,
                'column' => $reservation->column,
            ];
        }

        return view('admin.reservations', compact('reservations'));
    }

    /**
     * Show the form for the first step of creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('reservation.create', [
            'sessions' => Session::all(),
            'createReservationFirstStepInfo' => session('createReservationFirstStepInfo'),
        ]);
    }

    /**
     * Process the first step of the form for creating a new resource.
     *
     * @param ProcessFirstStepReservationRequest $request
     * @return RedirectResponse
     */
    public function processFirstStep(ProcessFirstStepReservationRequest $request): RedirectResponse
    {
        session(['createReservationFirstStepInfo' => $request->validated()]);

        return redirect()->route('reservation.create.second');
    }

    /**
     * Show the second step of the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function createSecondStep()
    {
        $createReservationFirstStepInfo = session('createReservationFirstStepInfo');

        $occupiedSeats = array_map(function ($seat) {
            return $seat['row'] . '-' . $seat['column'];
        },
            Session::find($createReservationFirstStepInfo['session'])
                ->reservations()
                ->select(['row', 'column'])
                ->get()
                ->toArray()
        );

        if (auth()->user()) {
            $userSeats = array_map(function ($seat) {
                return $seat['row'] . '-' . $seat['column'];
            },
                Reservation::where('session_id', $createReservationFirstStepInfo['session'])
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
     * Store a newly created resource in storage (Store second step).
     *
     * @param  ProcessSecondStepReservationRequest  $request
     * @return Application|Factory|View
     */
    public function store(ProcessSecondStepReservationRequest $request)
    {
        $seats = $request->validated()['seats'];
        $createReservationFirstStepInfo = $request->session()->pull('createReservationFirstStepInfo');

        if ($createReservationFirstStepInfo) {
            if (!auth()->user()) {
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

            foreach ($seats as $seat) {
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
                        . ') reserved the seat at '
                        . $reservation->row
                        . '-'
                        . $reservation->column
                        . ' for the "'
                        . $session->name
                        . '" theater play on '
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
     * Show the form for editing the specified resource.
     *
     * @param  Reservation $reservation
     * @return Application|Factory|View|RedirectResponse
     */
    public function edit(Reservation $reservation)
    {
        if (Gate::denies('edit', $reservation)) {
            return redirect()->route('user.reservations.show', auth()->user());
        }

        $occupiedSeats = array_map(
            function($seat)
            {
                return $seat['row'] . '-' . $seat['column'];
            },
            Reservation::where('session_id', $reservation->session_id)
                ->where(
                    function($q) use($reservation)
                    {
                        $q->where('row', '!=', $reservation->row);
                        $q->orWhere('column', '!=', $reservation->column);
                    }
                )
                ->select(['row','column'])
                ->get()
                ->toArray()
        );

        $userSeats = array_map(
            function ($seat)
            {
                return $seat['row'] . '-' . $seat['column'];
            },
            Reservation::where('session_id', $reservation->session_id)
                ->where('user_id', $reservation->user_id)
                ->where(
                    function($q) use($reservation)
                    {
                        $q->where('row', '!=', $reservation->row);
                        $q->orWhere('column', '!=', $reservation->column);
                    }
                )
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
     * @param  UpdateReservationRequest  $request
     * @param  Reservation  $reservation
     * @return Application|Factory|View|RedirectResponse
     */
    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        if (Gate::denies('update', $reservation)) {
            return redirect()->route('user.reservations.show', auth()->user());
        }

        $rowColumn = explode('-', $request->validated()['newseat']);

        $session = Session::find($reservation->session_id);

        Log::channel('reservations')
            ->info(auth()->user()->name
                . ' '
                . auth()->user()->surname
                . ' (id='
                . auth()->user()->id
                . ') changed the seat at '
                . $reservation->row
                . '-'
                . $reservation->column
                . ' for the seat at '
                . $rowColumn[0]
                . '-'
                . $rowColumn[1]
                . ' for the "'
                . $session->name
                . '" theater play on '
                . Carbon::parse($session->date)->format('d/m/Y H:i')
            );

        $reservation->update([
            'row' => $rowColumn[0],
            'column' => $rowColumn[1]
        ]);

        $reservations = [];
        $user = User::find($reservation->user_id);
        foreach ($user->reservations as $reservation) {
            $session = Session::find($reservation->session_id);
            $reservations[$session->name][Carbon::parse($session->date)->format('d/m/Y H:i')][] = [
                'id' => $reservation->id,
                'row' => $reservation->row,
                'column' => $reservation->column
            ];
        }

        if (auth()->user()->id !== $reservation->user_id) {
            return redirect()->route('reservation.index')->with('message', __('Reservation updated'));
        } else {
            return view('users.reservations', compact('reservations','user'))
                ->with('message', __('Reservation updated'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Reservation $reservation
     * @return RedirectResponse
     */
    public function destroy(Reservation $reservation): RedirectResponse
    {
        if (Gate::denies('delete', $reservation)) {
            return redirect()->route('user.reservations.show', auth()->user());
        }

        $session = Session::find($reservation->session_id);

        Log::channel('reservations')
            ->info(auth()->user()->name
                . ' '
                . auth()->user()->surname
                . ' (id='
                . auth()->user()->id
                . ') canceled the seat at '
                . $reservation->row
                . '-'
                . $reservation->column
                . ' for the "'
                . $session->name
                . '" theater play on '
                . Carbon::parse($session->date)->format('d/m/Y H:i')
            );

        $reservation->delete();

        return back()->with('message', __('Reservation deleted'));
    }
}
