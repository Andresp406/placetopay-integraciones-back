<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Trait\PlacetopayTrait;
use App\Http\Requests\SaleRequest;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class SaleController extends Controller
{
    use PlacetopayTrait;


    public function sale(SaleRequest $request)
    {
        $user = User::find(auth()->id());
        $product = Product::find($request->product_id);
        $total_price = $product->price * $request->amount;
        $code = 1;

        $this->checkoutRequest($code);
        

        // dd($total_price);
        $user->r_products()->attach([
            $request->product_id =>
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
        ]);
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
            'data' => [
               $data
            ]
        ]);
    }
}
