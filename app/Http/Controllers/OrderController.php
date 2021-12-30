<?php

namespace App\Http\Controllers;

use Dnetix\Redirection\PlacetoPay;

use App\Http\Requests\SaleRequest;
use App\Models\OrderRequestPayment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
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

        $placetopay = (new WebServiceController)->getClient();
        $ip = (new WebServiceController)->getRealIpAddr();
        $reference = $code;
        $requestPlacetopay = [
            'payment' => [
                'reference' => $reference,
                'description' => $order->description,
                'amount' => [
                    'currency' => 'USD',
                    'total' => $total_price,
                ],
            ],
            "buyer" => [
                "name" => $request->name,
                "email" => $request->email,
                "document" => $user->document,
                "documentType" => $user->type_document,
            ],
            'expiration' => date('c', strtotime(' + 2 days')),
            'returnUrl' => env('RETURN_URL').$reference,
            'ipAddress' => $ip,
            'userAgent' => $_SERVER['HTTP_USER_AGENT'],
        ];

         try {
            $response = $placetopay->request($requestPlacetopay);

            if ($response->isSuccessful()) {
                // Redirect the client to the processUrl or display it on the JS extension
                $this->createRequestPayment($order->id, $response->requestId(), $response->processUrl());
                $user->r_products()->attach([
                    $request->product_id =>
                    [
                        'amount'    => $request->amount,
                        'total_price' => $total_price
                    ]
                    ]);

                return response([
                    'ok' => true,
                    'message' => 'orden generada satisfactoriamente',
                    'data' => [
                        'url'=> $response->processUrl(),
                        'product' => $product,
                        'amount'  => $request->amount,
                        'total_price' => $total_price,
                    ],
                ]);               
            } else {
                // There was some error so check the message
                var_dump($response->status()->message());
            }
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }        
    }


    public function checkoutResponse(Request $request)
    {
        //dd($request->reference);
        $reference = 1;
        $order = Product::where('id', $reference)->first();
        $orderRequestPayment = OrderRequestPayment::where('order_id', $order->id)
        ->where('ending', 0)
        ->latest()
        ->first();
        $placetopay = (new WebServiceController)->getClient();
        $response = $placetopay->query($orderRequestPayment->request_id);
        $user = User::find(auth()->id());
        
        $data = Product::whereHas('r_user', function ($query) {
            return $query->where('users.id', auth()->id());

        })
        ->search($request->search)
        ->get();

        foreach($data as $da){
            $dataStatus = [
              'name' => $da['name'],
              'img' => $da['img'],
              'description' => $da['description'],
              'price' => $da['price'],
              'created_at' => $da['created_at'],
              'status' => $response->status()->status(),
              'updated_at' => $da['updated_at'],
              'id' => $da['id'],
          ]; 

        }
       // d($dataStatus);
 
        return response([
            'ok'    =>true,
            'message' => 'Transaction success',
            'data' => [
                'product' =>[ $dataStatus],
                'user' => $user,
            ],
        ]);  
    }

    
   /*  public function checkoutResponse(Request $request)
    {
        $this->mySales($request); */
        
        /* $placetopay = $this->getClient();
        
        $reference = $request->reference;
        $order = Product::where('id', $reference)->first();
        if ($order == false) {
            abort(404);
        }
        
        $orderRequestPayment = OrderRequestPayment::where('order_id', $order->id)
        ->where('ending', 0)
        ->latest()
        ->first();
        

        try {
            $response = $placetopay->query($orderRequestPayment->request_id);
            

            if ($response->isSuccessful()) {
                // In order to use the functions please refer to the RedirectInformation class

                if ($response->status()->isApproved()) {
                    $orderRequestPayment->status = $response->status()->status();
                    $orderRequestPayment->ending = 1;

                    $order->status = Product::STATUS_PAYED;
                    $order->update();
                    //$this->saveDatabase($response, $this->dataCheckout);
                    return redirect()->to("http://localhost:4200/orders?{$response->status()->status()}"); 

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
        } */
   // }

    

    private function createRequestPayment($orderId, $requestId, $requestUrl)
    {        
         OrderRequestPayment::create([
            'order_id' => $orderId,
            'request_id' => $requestId,
            'process_url' => $requestUrl,
        ]);
    }





    
}
