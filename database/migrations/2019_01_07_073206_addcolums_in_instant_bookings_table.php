<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddcolumsInInstantBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instant_bookings', function (Blueprint $table) {
            $table->char('Zip_code')->after('cutomer_id');
            $table->string('Services_id')->after('time');
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
            //
        });
    }
}
