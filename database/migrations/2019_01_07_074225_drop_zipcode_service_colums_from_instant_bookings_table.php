<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropZipcodeServiceColumsFromInstantBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instant_bookings', function (Blueprint $table) {
            $table->dropColumn(['Zip_code', 'Services_id']);
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
            $table->char('Zip_code');
            $table->string('Services_id');
        });
    }
}
