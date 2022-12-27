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
            $table->foreignId('user_id');
            $table->text('description')->nullable();
            $table->integer('status', false, true)->default(1)->comment('0 - inactive, 1 - active');
            $table->decimal('pnl', 19, 2, true)->nullable();
            $table->decimal('assets', 19, 2, true)->nullable();
            $table->string('binance_api_key', 64)->nullable();
            $table->string('binance_secret_key', 64)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->softDeletes();

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
