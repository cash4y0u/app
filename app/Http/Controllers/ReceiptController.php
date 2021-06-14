<?php

namespace App\Http\Controllers;

use App\Provision;
use Carbon\Carbon;
use App\Jobs\ReceiptMonthly;
use Illuminate\Http\Request;
use App\Http\Resources\ProvisionResource;

class ReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $date = now();


        $provisions = Provision::with('contract')
        ->whereDate('maturity', $date)
        ->where('status', 'pending')
        ->orderBy('time', 'desc')
        ->get();

        //$yesterday=Carbon::now()->subDays(1)->format('Y-m-d');
        //$provisions2=Provision::with('contract')
         //   ->whereDate('created_at', $yesterday)
         //   ->where('number', '1.0')
         //   ->orderBy('time', 'desc');
//
       // $provisions = Provision::with('contract')
        //    ->whereDate('maturity', $date)
         //   ->where('status', 'pending')
          //  ->orderBy('time', 'desc')
         //   ->union($provisions2)
         //   ->get();

        $itinerary = [];
        foreach ($provisions as $provision) {
            foreach ($provision->contract->customer->adresses as $address) {
                if ($address['favorite']) {
                    $itinerary[] = $address['street'] .", ". $address['number'] ." - ". $address['district']  ;
                }
            }
        }

        $collection = collect($itinerary);

        $collection = $collection->unique();





        $address = $collection->map(function ($item, $key) {
            return urlencode($item);
        });

        $daddr = implode('/', $address->toArray());


        $remaining = $provisions->filter(function ($value, $key) {
            return is_null($value['received_at']);
        });

        $collected = $provisions->filter(function ($value, $key) {
            return !is_null($value['received_at']);
        });



        return ProvisionResource::collection($provisions)->additional(['meta' => [
            'date' => [
                'day' => $date->format('d'),
                'weekname' => $date->formatLocalized('%A'),
                'month' => $date->formatLocalized('%B'),
                'year' => $date->format('Y')
            ],

            'remaining' => $remaining->count(),
            'collected' => $collected->count(),
            'total' => $provisions->count(),

            //'comgooglemaps' => "comgooglemaps://?saddr=Rua+Bryonia,+270&daddr={$daddr}&directionsmode=driving"

            'comgooglemaps' => "https://www.google.com/maps/dir/{$daddr}"


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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new ProvisionResource(Provision::find($id));
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
        $provision = Provision::find($id);

        $amount_paid = $request->input('amount_paid');

        if ($amount_paid == 0) {
            $provision->update([
                'maturity' => now()->addDays()
            ]);
        } else {
            ReceiptMonthly::dispatch($provision, $amount_paid);
        }
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
