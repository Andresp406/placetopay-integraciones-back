<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderResponseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_response', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('order_id');
            $table->string('name');
            $table->string('email');
            $table->string('product');
            $table->string('description');
            $table->float('price');
            $table->string('status');
            $table->string('status_message');

           $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_response');
    }
}
