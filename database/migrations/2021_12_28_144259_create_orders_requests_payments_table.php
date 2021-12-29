<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersRequestsPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('orders_requests_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->string('request_id');
            $table->string('process_url');
            $table->text('response')->nullable();
            $table->boolean('ending')->default(0);
            $table->string('status')->nullable();
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
       Schema::dropIfExists('orders_requests_payments');
    }
}
