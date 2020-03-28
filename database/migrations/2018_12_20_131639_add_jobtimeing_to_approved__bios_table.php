<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJobtimeingToApprovedBiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('approved__bios', function (Blueprint $table) {
        $table->time('starttime')->after('Bio');
        $table->time('endtime')->after('starttime');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('approved__bios', function (Blueprint $table) {
            //
        });
    }
}
