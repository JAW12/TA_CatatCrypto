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
            $table->text('name');
            $table->text('description')->nullable();
            $table->decimal('balance', 19, 2, false)->nullable();
            $table->string('balance_symbol', 255)->nullable();
            $table->decimal('pnl', 19, 2, false)->nullable();
            $table->decimal('amount_of_assets', 19, 2, false)->nullable();
            $table->string('binance_api_key', 64)->nullable();
            $table->string('binance_secret_key', 64)->nullable();
            $table->boolean('demo')->default(0);
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
