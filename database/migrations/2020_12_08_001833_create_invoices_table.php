<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string('invoice_number')->nullable();
            $table->date('invoice_date');
            $table->double('discount')->default(0);
            $table->unsignedBigInteger('visit_id');
            $table->enum('payment_method',['Efectivo','Tarjeta'])->nullable();
            $table->string('authorization_number')->nullable();
            $table->enum('payment_status',['Pendiente','Pago'])->default('Pendiente');

            $table->foreign('visit_id')->references('id')->on('visits');
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
