<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    const STATUS_CREATED = 'CREATED';
    const STATUS_PAYED = 'APPROVED';
    const STATUS_REJECTED = 'REJECTED'; 

        /**
         * The table associated with the model.
         *
         * @var string
         */
        protected $table = 'order';

        /**
         * The primary key associated with the table.
         *
         * @var string
         */
        protected $primaryKey = 'id';

        /**
         * The "type" of the primary key ID.
         *
         * @var string
         */
        protected $keyType = 'int';

        /**
         * The attributes that are mass assignable.
         *
         * @var array
         */
        protected $fillable = [
            'customer_id',
            'total',
            'code'
        ];

        /**
         * The attributes that should be cast to native types.
         *
         * @var array
         */
        protected $casts = [];

        /**
         * The accessors to append to the model's array form.
         *
         * @var array
         */
        protected $appends = [];

        /**
         * Indicates if the IDs are auto-incrementing.
         *
         * @var bool
         */
        public $incrementing = true;

        //TODO Mutators models Order
        //TODO Relations models Order
        //TODO Scopes models Order
}
