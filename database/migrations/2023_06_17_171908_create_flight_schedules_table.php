<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlightSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flight_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('flight_number')->nullable();
            $table->string('departure_airport')->nullable();
            $table->string('arrival_airport')->nullable();
            $table->string('departure_time')->nullable();
            $table->string('arrival_time')->nullable();
            $table->string('status')->default('active');
            $table->unsignedBigInteger('caller_type_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('airplane_id')->nullable();
            $table->unsignedBigInteger('alert_template_id')->nullable();
            $table->string('ticket_number')->nullable();
            $table->string('counter')->nullable();
            $table->unsignedBigInteger('userdetails_id')->nullable();
            $table->unsignedBigInteger('user_details_id')->nullable();
            $table->timestamps();

            $table->foreign('caller_type_id')->references('id')->on('caller_types');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('airplane_id')->references('id')->on('airplanes');
            $table->foreign('alert_template_id')->references('id')->on('alert_templates');
            $table->foreign('userdetails_id')->references('id')->on('user_details');
            $table->foreign('user_details_id')->references('id')->on('user_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flight_schedules');
    }
}

