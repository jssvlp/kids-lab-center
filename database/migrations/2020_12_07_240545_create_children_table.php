<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChildrenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('children', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('birth_date');
            $table->enum('gender',['Niño','Niña']);
            $table->string('health_insurance_id')->nullable();
            $table->unsignedBigInteger('dad_or_mom_id');
            $table->unsignedBigInteger('plan_id')->nullable();

            //Foreings
            $table->foreign('dad_or_mom_id')->references('id')->on('dad_or_moms')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('plans');
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
        Schema::dropIfExists('children');
    }
}
