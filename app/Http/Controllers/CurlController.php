<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CurlController extends Controller
{
    public function makeCurlRequest()
    {
        $url = 'https://api-sa1.pusher.com/apps/1805001/events?auth_key=c3067da7f8239d39e888&auth_timestamp=1719103748&auth_version=1.0&body_md5=a577f55657bcaf87bf0d8376d83ac134&auth_signature=a007422be22ad3450281e88a6cc539c008753b39c83e51f3c5875e78ca1d481b';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Desativa a verificação SSL
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $response = curl_exec($ch);
        if(curl_errno($ch)) {
            echo 'Erro ao realizar requisição cURL: ' . curl_error($ch);
        }
        curl_close($ch);

        echo $response;
    }
}
