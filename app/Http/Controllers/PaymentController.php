<?php

namespace App\Http\Controllers;

use App\Provision;
use Illuminate\Http\Request;
use App\Http\Resources\ProvisionResource;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    
        $provisions = Provision::with('contract')
        ->whereDate('received_at', now())
        ->where('amount_paid', '<>', 0)
        ->where('status', 'pending')
        ->orderBy('received_at', 'desc')->get();

        

        return ProvisionResource::collection($provisions)->additional(['meta' => [
            'remaining' => Provision::whereDate('maturity', now())->whereNull('received_at')->count(),
            'collected' => $provisions->count(),
            'total' => Provision::whereDate('maturity', now())->count(),
            'amount' => number_format($provisions->sum('amount_paid'), 2, ',', '.'),
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
        foreach($request->all() as $payment){
             Provision::find($payment['id'])->update([
                'status' => 'paid',
                'paid_at' => now()
            ]);
        }
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
