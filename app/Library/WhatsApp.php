<?php

namespace App\Library;

use GuzzleHttp\Client;

class WhatsApp
{
    public function __construct()
    {
        $this->guzzle = new Client([
            'verify' => false,
          
            'headers' => [
                'Authorization' => '9135400c93b327658e107a3b45754846c64704f8',
            ],
        ]);
    }

    public function contacts()
    {
        return($this->guzzle->get("http://v4.chatpro.com.br/chatpro-vj13k6a1ap/api/v1/contacts")->getBody());
    }

    public function qrcode()
    {
        return json_decode($this->guzzle->get("/generate_qrcode")->getBody(), false);
    }

    public function profile($number)
    {
        $response = $this->guzzle->post('/get_profile', [
            'number' => $number,
        ]);

        return json_decode($response->getBody(), false);
    }

    public function location($data)
    {
        $response = $this->guzzle->post('/send_location', [
            'address' => $data['address'],
            'lat' => $data['lat'],
            'lng' => $data['lng'],
            'name' => $data['name'],
            'number' => $data['number'],
        ]);

        return json_decode($response->getBody(), false);
    }

    public function message($data)
    {
        $response = $this->guzzle->post('/send_message', [
            'message' => $data['message'],
            'number' => $data['number'],
        ]);

        return json_decode($response->getBody(), false);
    }

    public function file($data)
    {
        $response = $this->guzzle->post('/send_message_file_from_url', [
            'caption' => $data['caption'],
            'url' => $data['url'],
            'number' => $data['number'],
        ]);

        return json_decode($response->getBody(), false);
    }

}
