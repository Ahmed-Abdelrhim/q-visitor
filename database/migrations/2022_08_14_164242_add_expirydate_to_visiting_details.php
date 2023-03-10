<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpirydateToVisitingDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('visiting_details', function (Blueprint $table) {
            $table->dateTime('expiry_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('visiting_details', function (Blueprint $table) {
            $table->dropColumn(['expiry_date']);
        });
    }
}
