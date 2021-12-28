<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function all(Request $request)
    {
        $products = Product::search($request->search)->get();

        return response()->json([
            'ok'    => true,
            'message' => 'success search',
            'data' => [
                'products' => $products,
            ]
        ]);
    }
}
