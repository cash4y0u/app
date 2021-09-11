<?php

namespace App\Http\Controllers;

use App\Provision;
use Illuminate\Http\Request;
use App\Http\Resources\ProvisionPendingResource;
use App\Http\Resources\ProvisionResource;
use App\Contract;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class ProvisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 

        $provisions = Provision::whereBetween('maturity',[now()->subMonth(6), now()->addDays(5)])
        ->orderBy('maturity', 'asc')
        ->cursor();

    return ProvisionPendingResource::collection($provisions);
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
     * @param  \App\Provision  $provision
     * @return \Illuminate\Http\Response
     */
    public function show(Provision $provision)
    {
        // return Cache::remember("provisions", 60, function () use($provision){
        return new ProvisionResource(Provision::find($provision->id));
        // });

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Provision  $provision
     * @return \Illuminate\Http\Response
     */
    public function edit(Provision $provision)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Provision  $provision
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Provision $provision)
    {

        $provision = Provision::find($provision->id);

        $provision->update([
            'status' => 'paid',
            'amount_paid' => ($request->input('minimum_payment') ? $provision->contract->amount_rate : $provision->amount),
            'paid_at' => now()
        ]);

        if ($request->input('minimum_payment')) {

            $last = $provision->contract->provisions->last();

            switch ($provision->contract->cycle) {
                case 'daily':
                    $maturity = Carbon::parse($last->maturity)->addDay();
                    break;
                case 'weekly':
                    $maturity = Carbon::parse($last->maturity)->addWeek();
                    break;
                case 'fortnightly':
                    $maturity = Carbon::parse($last->maturity)->addWeek(2);
                    break;
                case 'monthly':
                    $maturity = Carbon::parse($last->maturity)->addMonth();
                    break;
            }
            $provision->contract->provisions()->create([
                'number' => $last->number + 1,
                'amount' => $last->amount,
                'maturity' => $maturity
            ]);
        }
        Cache::flush();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provision  $provision
     * @return \Illuminate\Http\Response
     */
    public function destroy(Provision $provision)
    {
        //
    }
}
