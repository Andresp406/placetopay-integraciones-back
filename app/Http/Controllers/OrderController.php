<?php

namespace App\Http\Controllers;

use Dnetix\Redirection\PlacetoPay;

use App\Http\Requests\SaleRequest;
use App\Models\Order;
use App\Models\OrderRequestPayment;
use App\Models\OrderResponse;
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
                (new ProductController)->createOrder($request, $user);

                $this->createRequestPayment($order->id, $response->requestId(), $response->processUrl());
                $user->r_products()->attach([
                    $request->product_id =>
                    [
                        'amount'    => $request->amount,
                        'total_price' => $total_price
                    ]
                    ]);
                    $this->req = $reference;

                    
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
        $reference = Order::where('id', auth()->id())->first();
    
        $order = Product::where('id', $reference->code)->first();
    
        $orderRequestPayment = OrderRequestPayment::where('order_id', $order->id)
        ->where('ending', 0)
        ->latest()
        ->first();
        $placetopay = (new WebServiceController)->getClient();
        $response = $placetopay->query($orderRequestPayment->request_id);
        $orderRequestPayment->status = $response->status()->status();
        $orderRequestPayment->response = $response->status()->message();
        $orderRequestPayment->update();
        
        $user = User::find(auth()->id());
        
        $data = Product::whereHas('r_user', function ($query) {
            return $query->where('users.id', auth()->id());

        })
        ->search($request->search)
        ->get();

         foreach($data as $d){
         
            OrderResponse::create([
                'id_user'=> $user->id,
                'name' => $user->first_name." ". $user->last_name,              
                'email' => $user->email,
                'product' => $d->name,              
                'description' => $d->description,
                'price' => $d->price,
                'status' => $orderRequestPayment->status, 
                'status_message' => $orderRequestPayment->response, 
                
            ]);        
        }
        $dataStatus =  OrderResponse::where('email', $user->email)->get();
        return response([
            'ok'    =>true,
            'message' => 'Transaction success',
            'data' => $dataStatus,
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
