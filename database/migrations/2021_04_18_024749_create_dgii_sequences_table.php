<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDgiiSequencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dgii_sequences', function (Blueprint $table) {
            $table->id();
            $table->integer('sequence');
            $table->string('full_sequence');
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('dgii_numbering_config_id');

            $table->foreign('invoice_id')->references('id')->on('invoices');
            $table->foreign('dgii_numbering_config_id')->references('id')->on('dgii_numbering_configs');
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
        Schema::dropIfExists('dgii_sequences');
    }
}
