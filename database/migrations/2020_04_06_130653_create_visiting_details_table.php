<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visiting_details', function (Blueprint $table) {
            $table->id();
            $table->string('reg_no')->unique();

            $table->string('purpose',225)->nullable();
            $table->string('company_name',100)->nullable();
            $table->string('company_employee_id')->nullable();
            $table->dateTime('checkin_at')->nullable();
            $table->dateTime('checkout_at')->nullable();
            $table->tinyInteger('is_new_scan')->default(1);

            $table->unsignedTinyInteger('status');
            $table->unsignedBigInteger('user_id');

            $table->unsignedBigInteger('creator_employee');
            $table->unsignedBigInteger('employee_id');

            $table->unsignedInteger('type_id')->nullable();
            $table->unsignedBigInteger('visitor_id');

            $table->integer('shipment_id')->default(0);
            $table->text('shipment_number')->nullable();
            $table->integer('qulaity_check')->nullable();


            $table->string('plate_no',100)->nullable();

            $table->integer('is_contractor')->default(0);

            $table->string('car_type')->nullable();
            $table->tinyInteger('approval_status')->default(0); // length = 1
            
            $table->auditColumn();
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
        Schema::dropIfExists('bookings');
    }
}
