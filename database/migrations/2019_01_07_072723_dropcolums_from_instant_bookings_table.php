<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropcolumsFromInstantBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instant_bookings', function (Blueprint $table) {
             $table->dropColumn(['lat', 'long','status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instant_bookings', function (Blueprint $table) {
            $table->string('lat');
            $table->string('long');
            $table->enum('status', [0 =>'inprogress', 1 =>'providerAccepted',2 =>'workcompleted',3 =>'deletebycustomer',4 =>'deletebyprovider']);
        });
    }
}
