<?php

namespace App\Http\Controllers;

use App\Contract;
use App\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Jobs\CreateProvision;
use App\Jobs\CreateDailyProvision;
use App\Jobs\CreateMonthlyProvision;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ContractCreated;
use App\Http\Resources\ContractResource;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contracts = Contract::with('customer')->orderBy('created_at', 'desc')->take(30)->get();
        return ContractResource::collection($contracts)->additional(['meta' => [
            'total' => $contracts->count(),
        ]]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $customer = Customer::find($request->input('customer_id'));

        $contract = $customer->contracts()->create($request->only([
            'amount',
            'amount_profit',
            'amount_provision',
            'amount_rate',
            'amount_total',
            'cycle',
            'maturity',
            'rate',
            'rate_daily',
            'split'
        ]));

        if($contract->cycle == 'daily'){
            CreateDailyProvision::dispatch($contract);
        }else{
            CreateMonthlyProvision::dispatch($contract);
        }

        //Auth::user()->notify(new ContractCreated($contract));

        return $contract;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function show(Contract $contract)
    {
        return new ContractResource(Contract::with(['customer', 'provisions'])->find($contract->id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function edit(Contract $contract)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contract $contract)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contract $contract)
    {
        $contract->delete();
    }
}
