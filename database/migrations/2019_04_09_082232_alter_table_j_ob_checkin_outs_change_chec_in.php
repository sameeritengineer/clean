<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableJObCheckinOutsChangeChecIn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('j_ob_checkin_outs', function (Blueprint $table) {
            $table->time('checkIn')->change();
            $table->time('checkOut')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('j_ob_checkin_outs', function (Blueprint $table) {
            //
        });
    }
}
