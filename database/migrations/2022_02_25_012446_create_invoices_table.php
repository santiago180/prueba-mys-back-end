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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('number')->comment('Numero de factura');
            $table->dateTime('date')->comment('Fecha y hora');
            $table->unsignedBigInteger('transmitter_id')->comment('Emisor');
            $table->unsignedBigInteger('receiver_id')->comment('Receptor o comprador');
            $table->double('value_before_iva')->nullable()->comment('Valor antes de IVA');
            $table->double('iva')->nullable()->comment('IVA');
            $table->double('value_to_pay')->nullable()->comment('Valor a pagar');
            
            $table->foreign('transmitter_id')->references('id')->on('contacts');
            $table->foreign('receiver_id')->references('id')->on('contacts');
            
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
        Schema::dropIfExists('invoices');
    }
};
