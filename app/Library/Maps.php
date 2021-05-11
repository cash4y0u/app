<?php
namespace App\Library;

use Illuminate\Support\Carbon;

class Maps
{
    private $api = 'https://maps.googleapis.com/maps/api/distancematrix/json';
    private $api_key = 'AIzaSyAYJKek-sgDLP10aH-XW0RMtQGvX5lBoDc';

    private $origins = 'Rua Bryonia, 270';
    private $destinations = '';


    function __construct()
    {

    }

    public function from($origins)
    {
        $this->origins = $origins;
    }

    public function to($destinations){
        $this->destinations = $destinations;
    }

    function time(){

        $query = http_build_query([
            'units' => 'imperial',
            'origins' => $this->origins,
            'destinations' => $this->destinations,
            'key' => $this->api_key
        ]);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "{$this->api}?{$query}");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $data = curl_exec($ch);

        curl_close($ch);

        $json = json_decode($data, true);

        return $json['rows'][0]['elements'][0]['duration']['value'];


    }

}
