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
            $table->foreignId('id_user');

            $table->string('status');
            $table->string('id_transaction')->nullable();
            $table->string('id_order')->nullable();
            $table->string('gross_amount');
            $table->string('bank_name')->nullable();
            $table->string('payment_name')->nullable();
            $table->string('payment_type');
            $table->string('payment_code')->nullable();
            $table->string('url_invoice')->nullable();

            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users');
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
