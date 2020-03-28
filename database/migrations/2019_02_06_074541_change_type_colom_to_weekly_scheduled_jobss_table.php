<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTypeColomToWeeklyScheduledJobssTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('weekly_scheduled_jobs', function (Blueprint $table) {
            $table->string('days')->change();
            $table->string('time')->change();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('weekly_scheduled_jobs', function (Blueprint $table) {
            //
        });
    }
}
