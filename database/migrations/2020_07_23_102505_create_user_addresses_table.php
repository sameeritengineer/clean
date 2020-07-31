<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();           
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('country_id')->unsigned();           
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->integer('state_id')->unsigned();           
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
            $table->integer('city_id')->unsigned();           
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->integer('zipcode_id')->unsigned();           
            $table->foreign('zipcode_id')->references('id')->on('zipcodes')->onDelete('cascade');
            $table->char('lat')->nullable();
            $table->char('long')->nullable(); 
            $table->text('address');
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
        Schema::dropIfExists('user_addresses');
    }
}
