<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function all(Request $request)
    {
        $products = Product::search($request->search)->get();

        return response()->json([
            'ok'    => true,
            'message' => 'Busqueda Exitosa',
            'data' => [
                'products' => $products,
            ]
        ]);
    }


    public function createOrder(Request $request, $user)
    {
        $product = Product::find($request->product_id);
        
        //$quantity = $request->quantity;
        $total = $product->price;
        $customerId = $user->id;
        $code = $request->product_id;
        
        $this->create($customerId, $total, $code);        
    }


    
 private static function create($customerId, float $total, $code)
 {
     return Order::create([
         'customer_id' => $customerId,
         'total' => $total,
         'code' => $code,
     ]);
 }
}
