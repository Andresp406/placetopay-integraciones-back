<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SoapClient;
use SoapFault;

class PseController extends Controller
{
    public function pseCheckout()
    {
        $url = env('BASE_URL_PSE');
        try {
            $client = new SoapClient($url, ["trace" => 1]);
           // $result = $client->ResolveIP(["ipAddress" => $argv[1], "licenseKey" => "0"]);
            return response([
                'ok' => true,
                'message'=> 'success',
                'data'=> [],
            ]);
        } catch (SoapFault $e) {
            echo $e->getMessage();
        }
    }
}
