<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Provision;
use App\Library\Calendar;
use Illuminate\Support\Facades\Auth;

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
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function calendar()
    {
        $provisions = Provision::with('contract')->where('status', 'pending')->orderBy('maturity', 'asc')->get();

        $calendar = new Calendar();
        $calendar->name("Cash4You");
        $calendar->description('Controle de empréstimo');
        $calendar->color('#1976d2');
        foreach ($provisions as $provision) {
            $calendar->event([
                'id' => $provision->id,
                'start' => $provision->maturity,
                'end' => $provision->maturity,
                'title' => "{$provision->contract->customer->name} - R$ {$provision->formattedAmount}",
                'trigger' => 'PT9H',
                'url' => url("/provisions/{$provision->id}")
            ]);
        }
    }
}
