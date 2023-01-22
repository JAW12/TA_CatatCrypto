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
        Schema::create('timeframe_trade', function (Blueprint $table) {
            $table->id();
            $table->foreignId('timeframe_id');
            $table->foreignId('trade_id');
            $table->int('picture_type')->comment('0 - tv, 1 - ss');
            $table->text('url_picture');
            $table->timestamps();

            $table->foreign('timeframe_id')->references('id')->on('timeframes')->onDelete('CASCADE');
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
        Schema::dropIfExists('timeframe_trade');
    }
};
