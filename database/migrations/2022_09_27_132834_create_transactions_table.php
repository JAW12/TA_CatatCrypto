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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('membership_id')->nullable();

            $table->string('name');
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();

            $table->string('status');
            $table->string('id_transaction')->nullable();
            $table->string('id_order')->nullable();
            $table->string('gross_amount');
            $table->string('bank_name')->nullable();
            $table->string('payment_name')->nullable();
            $table->string('payment_type');
            $table->dateTime('payment_time')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('membership_id')->references('id')->on('memberships')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
