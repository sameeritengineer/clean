<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstantBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instant_bookings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cutomer_id');
            $table->string('lat');
            $table->string('long');
            $table->date('date');
            $table->time('time');
            $table->enum('status', [0 =>'inprogress', 1 =>'providerAccepted',2 =>'workcompleted',3 =>'deletebycustomer',4 =>'deletebyprovider']);
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
        Schema::dropIfExists('instant_bookings');
    }
}
