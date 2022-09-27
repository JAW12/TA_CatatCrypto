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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user');
            $table->text('description')->nullable();
            $table->integer('status', false, true)->default(1)->comment('0 - inactive, 1 - active');
            $table->decimal('pnl', 19, 2, true)->nullable();
            $table->decimal('assets', 19, 2, true)->nullable();
            $table->string('binance_api_key', 64)->nullable();
            $table->string('binance_secret_key', 64)->nullable();
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
        Schema::dropIfExists('wallets');
    }
};
