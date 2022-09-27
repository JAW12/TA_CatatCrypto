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
            $table->foreignId('id_timeframe');
            $table->foreignId('id_trade');
            $table->text('url_picture');
            $table->timestamps();

            $table->foreign('id_timeframe')->references('id')->on('timeframes');
            $table->foreign('id_trade')->references('id')->on('trades')->onDelete('cascade');

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
