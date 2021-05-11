<?php
namespace App\Library;

use Illuminate\Support\Carbon;

class Geocode
{
    private $api = 'https://maps.googleapis.com/maps/api/geocode/json';
    private $api_key = 'AIzaSyAYJKek-sgDLP10aH-XW0RMtQGvX5lBoDc';

    private $address = '';


    function __construct()
    {

    }

    public function to($address){
        $this->address = $address;
    }

    function location(){

        $query = http_build_query([
            'address' => $this->address,
            'key' => $this->api_key
        ]);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "{$this->api}?{$query}");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $data = curl_exec($ch);

        curl_close($ch);

        $json = json_decode($data, true);

        return $json['results'][0]['geometry']['location'];


    }

}
