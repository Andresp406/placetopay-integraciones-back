<?php

namespace App\Http\Controllers;

use Dnetix\Redirection\PlacetoPay;

use App\Http\Requests\SaleRequest;
use App\Models\OrderRequestPayment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class SaleController extends Controller
{

   public function sale(SaleRequest $request)
    {
        $user = User::find(auth()->id());
        $product = Product::find($request->product_id);
        $total_price = $product->price * $request->amount;
        $code = $request->product_id;

     

        $order = Product::where('id', $code)->first();
        if ($order == false) {
            abort(404);
        }

        $placetopay = $this->getClient();

        $reference = $code;
        $request = [
            'payment' => [
                'reference' => $reference,
                'description' => $order->description,
                'amount' => [
                    'currency' => 'USD',
                    'total' => $total_price,
                ],
            ],
            "buyer" => [
                "name" => $user->first_name . ' ' . $user->last_name,
                "email" => $user->email,
                "document" => $user->document,
                "documentType" => $user->type_document,
                //"mobile" => 
            ],
            'expiration' => date('c', strtotime(' + 2 days')),
            'returnUrl' => route('response.checkout') . '?reference=' . $reference,
            'ipAddress' => '127.0.0.1',
            'userAgent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36',
        ];

         try {
            $response = $placetopay->request($request);

            if ($response->isSuccessful()) {
                // Redirect the client to the processUrl or display it on the JS extension
                $this->createRequestPayment($order->id, $response->requestId(), $response->processUrl());
                return response([
                    'ok' => true,
                    'message' => 'order purchase successful generate',
                    'data' => $response->processUrl(),
                ]);               
            } else {
                // There was some error so check the message
                var_dump($response->status()->message());
            }
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }

        
    }

    public function mySales(Request $request)
    {
        $data = Product::whereHas('r_user', function ($query) {
            return $query->where('users.id', auth()->id());

        })
        ->search($request->search)
        ->get();

        return response([
            'ok'    =>true,
            'message' => 'Transaction success',
            'data' => [$data]
        ]);  
    }

    
    public function checkoutResponse(Request $request)
    {
        
        $reference = $request->reference;
        $order = Product::where('id', $reference)->first();
        if ($order == false) {
            abort(404);
        }
        
        $orderRequestPayment = OrderRequestPayment::where('order_id', $order->id)
        ->where('ending', 0)
        ->latest()
        ->first();
        
        $placetopay = $this->getClient();

        try {
            $response = $placetopay->query($orderRequestPayment->request_id);
            

            if ($response->isSuccessful()) {
                // In order to use the functions please refer to the RedirectInformation class

                if ($response->status()->isApproved()) {
                    $orderRequestPayment->status = $response->status()->status();
                    $orderRequestPayment->ending = 1;

                    $order->status = Product::STATUS_PAYED;
                    $order->update();
                    $this->saveDatabase($response);
                }

                $orderRequestPayment->status = $response->status()->status();
                $orderRequestPayment->response = json_encode($response->toArray());
                $orderRequestPayment->update();

           
                return redirect()->to("http://localhost:4200/orders?{$response->status()->status()}"); 
           
            } else {
                // There was some error with the connection so check the message
                print_r($response->status()->message() . "\n");
            }
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
    }

    public function saveDatabase($response){
        $total_price = $response->request()->payment()->amount()->total();

        dd($response, auth()->user());
      /*$user->r_products()->attach([
            $product->id =>
            [
                'amount'    => $request->amount,
                'total_price' => $total_price
            ]
            ]);
        
        return response([
            'ok'    =>true,
            'message' => 'Transaction success',
            'data' => [
                'product' => $product,
                'amount'  => $request->amount,
                'total_price' => $total_price,
               
            ]
        ]);  */
  
    }

    private function getClient()
    {
        return new PlacetoPay([
            'login' => config('placetopay.login'), // Provided by PlacetoPay
            'tranKey' => config('placetopay.trankey'), // Provided by PlacetoPay
            'baseUrl' => config('placetopay.baseUrl'),
            'timeout' => 10, // (optional) 15 by default
        ]);
    }

    private function createRequestPayment($orderId, $requestId, $requestUrl)
    {
        
         OrderRequestPayment::create([
            'order_id' => $orderId,
            'request_id' => $requestId,
            'process_url' => $requestUrl,
        ]);
        

    }





    
}
