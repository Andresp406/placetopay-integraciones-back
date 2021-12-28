<?php


namespace App\Http\Controllers\Trait;

use App\Models\Product;

trait PlacetopayTrait{

    public function checkoutRequest($code =null)
    {
        //dd('cualquir cosa');
        $order = Product::where('id', $code)->first();

        if ($order == false) {
            abort(404);
        }

        $placetopay = $this->getClient();

        $reference = $code;
        $request = [
            'payment' => [
                'reference' => $reference,
                'description' => 'Pagando producto infinito',
                'amount' => [
                    'currency' => 'USD',
                    'total' => $order->total,
                ],
            ],
            "buyer" => [
                "name" => $order->customer->name,
                // "surname" => "Hoppe",
                "email" => $order->customer->email,
                // "document" => "1040035000",
                // "documentType" => "CC",
                "mobile" => $order->customer->mobile
            ],
            'expiration' => date('c', strtotime(' + 2 days')),
            'returnUrl' => route('checkout.response') . '?reference=' . $reference,
            'ipAddress' => '127.0.0.1',
            'userAgent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36',
        ];

        try {
            $response = $placetopay->request($request);

            if ($response->isSuccessful()) {
                // Redirect the client to the processUrl or display it on the JS extension
                $this->createRequestPayment($order->id, $response->requestId(), $response->processUrl());

                return response()->redirectTo($response->processUrl());
            } else {
                // There was some error so check the message
                var_dump($response->status()->message());
            }
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
    }



protected function getClient()
    {
        return new PlacetoPay([
            'login' => config('placetopay.login'), // Provided by PlacetoPay
            'tranKey' => config('placetopay.trankey'), // Provided by PlacetoPay
            'baseUrl' => config('placetopay.baseUrl'),
            'timeout' => 10, // (optional) 15 by default
        ]);
    }
} 