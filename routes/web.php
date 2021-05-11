<?php
use App\Contract;
use App\Customer;
use App\Provision;


use Carbon\Carbon;
use App\Library\Geocode;
use App\Library\WhatsApp;
use Carbon\CarbonInterval;
use Illuminate\Support\Arr;
use App\Http\Resources\ClienteResource;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/export', function () {
    return ClienteResource::collection(Customer::all());
});



Route::middleware('auth:api')->get('/calendar', 'UserController@calendar');

Route::get('/destinations', function () {
    $date = now();

        

    $provisions = Provision::with('contract')
    ->whereDate('maturity', $date)
    ->where('status', 'pending')
    ->orderBy('time', 'desc')
    ->get();


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



    return redirect()->away("https://www.google.com/maps/dir/{$daddr}");
});

Route::get('/whatsapp', function () {



    // $whatsapp = new WhatsApp();
    
    // return $whatsapp->contacts();
});


Route::get('/favorite', function () {
    $customers = Customer::all();

    foreach ($customers as $customer) {
        foreach ($customer->adresses as $address) {
            $array[$customer->id][] = Arr::set($address, "favorite", false);
        }

        $customer->update([
        'adresses' => $array[$customer->id]
    ]);
    }
});


Route::get('/{vue_capture?}', function () {
    return view('welcome');
})->where('vue_capture', '[\/\w\.-]*');
