<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('membership_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membership_id');
            $table->foreignId('user_id');
            $table->integer('status')->default(1)->comment('0 - inactive, 1 - active');
            $table->dateTime('membership_start');
            $table->dateTime('membership_expiration');
            $table->timestamps();

            $table->foreign('membership_id')->references('id')->on('memberships')->onDelete('CASCADE');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('membership_user');
    }
};
