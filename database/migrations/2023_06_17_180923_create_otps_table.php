<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateOtpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('otps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('otp');
            $table->timestamp('expiration')->nullable();
            $table->timestamps();
        });

        // Set expiration default value to 1 hour (60 minutes)
        DB::statement('ALTER TABLE otps MODIFY COLUMN expiration TIMESTAMP DEFAULT CURRENT_TIMESTAMP + INTERVAL 60 MINUTE');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('otps');
    }
}

