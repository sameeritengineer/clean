<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeStatusToInstantScheduleJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instant_schedule_jobs', function (Blueprint $table) {
             $table->dropColumn('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instant_schedule_jobs', function (Blueprint $table) {
            $table->enum('status', [0 =>'jobAccepted', 1 =>'inprogress',2 =>'deletebycustomer',3 =>'deletebyprovider']);
        });
    }
}
