<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('entity_id')->nullable()->unsigned();
            $table->foreign('entity_id')->references('id')->on('entities')->onDelete('set null');
            $table->bigInteger('identification')->nullable();
            $table->integer('restaurant_code')->nullable();
            $table->integer('invoice_code')->nullable();
            $table->integer('product_code')->nullable();
            $table->string('sale_type')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('value')->nullable();
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
        Schema::dropIfExists('invoices');
    }
}
