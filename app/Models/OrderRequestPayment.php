<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRequestPayment extends Model
{
    protected $table = 'orders_requests_payments';

    protected $fillable = [
        'order_id',
        'request_id',
        'process_url',
        'response',
        'ending',
    ];


}
