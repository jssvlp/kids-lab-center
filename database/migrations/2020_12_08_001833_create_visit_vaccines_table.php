<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitVaccinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visit_vaccines', function (Blueprint $table) {
            $table->primary(['visit_id','vaccine_id']);
            $table->unsignedBigInteger('visit_id');
            $table->unsignedBigInteger('vaccine_id');
            $table->foreign('visit_id')->references('id')->on('visits')->onDelete('cascade');
            $table->foreign('vaccine_id')->references('id')->on('vaccines');
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
        Schema::dropIfExists('visit_vaccines');
    }
}
