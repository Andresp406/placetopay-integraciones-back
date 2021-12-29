<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PseController;
use App\Http\Controllers\SaleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');

Route::group(['prefix' => 'product'], function() {
    Route::get('all', [ProductController::class, 'all'])->name('product.all');
});
Route::get('/sale/pse', [PseController::class, 'pseCheckout'])->name('checkout.pse');

Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::get('/sale/my-sales', [SaleController::class, 'mySales'])->name('sale.my-sales');
    Route::get('/response-checkout', [SaleController::class, 'checkoutResponse'])->name('response.checkout');
    Route::get('/me', [AuthController::class, 'me'])->name('auth.me');
    Route::post('/sale', [SaleController::class, 'sale'])->name('sale.sale');


});
