<?php

namespace App\Http\Controllers;

use App\Http\Requests\session\StoreSessionRequest;
use App\Http\Requests\session\UpdateSessionRequest;
use App\Models\Session;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|RedirectResponse
     */
    public function index()
    {
        if (Gate::denies('index', auth()->user()))
        {
            return redirect()->route('reservation.create');
        }

        return view('session.sessions', [
            'sessions' => Session::orderBy('date')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreSessionRequest  $request
     * @return RedirectResponse
     */
    public function store(StoreSessionRequest $request): RedirectResponse
    {
        if (Gate::denies('store', Session::class))
        {
            return redirect()->route('reservation.create');
        }

        Session::create($request->validated());

        return redirect()->route('session.index')->with('message', __('Session created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Session $session
     * @return Application|Factory|View|RedirectResponse
     */
    public function edit(Session $session)
    {
        if (Gate::denies('edit', $session))
        {
            return redirect()->route('reservation.create');
        }

        return view('session.edit-session', [
            'session' => $session
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateSessionRequest $request
     * @param Session $session
     * @return RedirectResponse
     */
    public function update(UpdateSessionRequest $request, Session $session): RedirectResponse
    {
        if (Gate::denies('update', $session))
        {
            return redirect()->route('reservation.create');
        }

        $session->update($request->validated());

        return redirect()->route('session.index')->with('message', __('Session updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Session $session
     * @return RedirectResponse
     */
    public function destroy(Session $session): RedirectResponse
    {
        if (Gate::denies('delete', Session::class))
        {
            return redirect()->route('reservation.create');
        }

        $session->delete();

        return redirect()->back()->with('message', __('Session deleted'));
    }
}
