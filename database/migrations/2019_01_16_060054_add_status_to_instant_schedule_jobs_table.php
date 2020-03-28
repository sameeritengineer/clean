<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToInstantScheduleJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instant_schedule_jobs', function (Blueprint $table) 
        {

          /*   0 =>'jobAccepted', 
               1 =>'inprogress',
               2 =>'deletebycustomer',
               3 =>'deletebyprovider'*/

            $table->enum('status', ['0','1','2','3'])->after('provider_id');
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
            //
        });
    }
}
