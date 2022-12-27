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
        Schema::create('strategy_trade', function (Blueprint $table) {
            $table->id();
            $table->foreignId('strategy_id');
            $table->foreignId('trade_id');
            $table->timestamps();

            $table->foreign('strategy_id')->references('id')->on('strategies')->onDelete('CASCADE');
            $table->foreign('trade_id')->references('id')->on('trades')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('strategy_trade');
    }
};
