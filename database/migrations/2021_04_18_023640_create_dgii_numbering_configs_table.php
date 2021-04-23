<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDgiiNumberingConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dgii_numbering_configs', function (Blueprint $table) {
            $table->id();
            $table->integer('init');
            $table->integer('end');
            $table->date('config_date')->default(now());
            $table->boolean('completed')->default(false);
            $table->boolean('active')->default(false);
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
        Schema::dropIfExists('dgii_numbering_configs');
    }
}
