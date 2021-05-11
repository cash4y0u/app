<?php

use App\Http\Resources\ProvisionResource;

use GuzzleHttp\Client;
use Carbon\Carbon;
use App\Customer;
use App\Contract;
use App\Provision;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::post('/auth', 'AuthController@authenticate');



Route::middleware('auth:api')->get('/statistics', function () {


$multiplied = Provision::with('contract')->get()->groupBy(function ($val) {
    return Carbon::parse($val->maturity)->format('Y');
})->map(function ($item) {

    return number_format($item->map(function ($contract){
        return $contract->contract->amount_rate;
     })->sum(), 2, ',', '.');
});


   

    return [
        'customers' => Customer::count(),
        'contracts' => Contract::where('status','open')->count(),
        'invested' => number_format(Contract::all()->sum('amount'), 2, ',', '.'),
        'yielded' => number_format(Provision::all()->sum('amount_paid'), 2, ',', '.'),
        'prediction' => [
            'lastProvision' => Provision::orderBy('maturity', 'desc')->first()->maturity->diffForHumans(),
            'details' => $multiplied,
        ]
    ];
});

Route::middleware('auth:api')->group(function () {
    Route::get('/charts', function (App\Provision $provision) {

        $provisions = $provision
            ->with('contract')
            ->whereYear('maturity', '=', date('Y'))
            //->whereMonth('maturity', '=', 5)
            ->orderBy('maturity', 'asc')
            ->get();

        foreach ($provisions as $pro) {
            $array[] = [
                'maturity' => $pro->maturity,
                'amount' => $pro->contract->amount_provision - $pro->contract->amount_rate,
                'amount_provision' => $pro->contract->amount_provision,
                'amount_rate' => $pro->contract->amount_rate
            ];
        }
        return collect($array)->groupBy(function ($val) {
            return Carbon::parse($val['maturity'])->format('Y-m');
        })->map(function ($amount, $month) {
            return [
                'month' => Carbon::parse($month)->formatLocalized('%B %Y'),
                'amount' => $amount->sum('amount'),
                'amount_provision' => $amount->sum('amount_provision'),
                'amount_rate' => $amount->sum('amount_rate'),
            ];
        })->values();
    });


    Route::post('/estimate', 'ContractController@estimate');
    Route::get('/auth/user', 'AuthController@user');

    Route::post('/customers/upload', 'CustomerController@uploadFile');
    Route::resource('customers', 'CustomerController');
    Route::resource('contracts', 'ContractController');
    Route::resource('provisions', 'ProvisionController');
    Route::resource('receipts', 'ReceiptController');
    Route::resource('payments', 'PaymentController');

    Route::get('/contracts/{contract}/provisions', function (App\Contract $contract) {
        return ProvisionResource::collection($contract->provisions()->orderBy('number', 'asc')->get());
    });

    Route::get('/cep/{cep}', function ($cep) {
        $guzzle = new Client();
        $zipcode = preg_replace('/[^0-9]/', '', $cep);
        return $guzzle->get("https://viacep.com.br/ws/{$zipcode}/json/")->getBody();
    });
});
