<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id')->comment('llave foranea de la factura');
            $table->unsignedBigInteger('item_id')->comment('llave foranea del item');
            $table->integer('quantity')->comment('Cantidad');
            $table->double('unit_value')->comment('Valor Unitario');
            $table->double('iva')->comment('IVA');
            $table->double('total_value')->comment('Valor Total');


            $table->foreign('invoice_id')->references('id')->on('invoices');
            $table->foreign('item_id')->references('id')->on('items');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_items');
    }
};
