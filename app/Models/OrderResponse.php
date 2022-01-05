<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderResponse extends Model
{
    use HasFactory;

        /**
         * The table associated with the model.
         *
         * @var string
         */
        protected $table = 'order_response';

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
            'name',
            'product',
            'email',
            'price',
            'description',
            'status_message',
            'status',
            'id_user'
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

        //TODO Mutators models OrderResponse
        //TODO Relations models OrderResponse
        //TODO Scopes models OrderResponse
}
