<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('firstName');
            $table->string('lastName');
            $table->text('address')->nullable();
            $table->string('mobileTelephone')->nullable();
            $table->string('workTelephone')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('status')->default('pending'); 
            $table->string('nationality')->nullable();
            $table->string('country')->nullable();
            $table->string('nid')->nullable();
            $table->string('passport')->nullable();
            $table->string('language')->nullable();
            $table->string('placeOfIssue')->nullable();
            $table->string('workEmail')->nullable();
            $table->string('userType');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_details');
    }
}
