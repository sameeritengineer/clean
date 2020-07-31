<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZipcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zipcodes', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('city_id')->nullable()->unsigned();            
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->char('zipcode')->unique();
            $table->char('near_by')->nullable();
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
        Schema::dropIfExists('zipcodes');
    }
}
