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
            $table->foreignId('id_strategy');
            $table->foreignId('id_trade');
            $table->timestamps();

            $table->foreign('id_strategy')->references('id')->on('strategies')->onDelete('cascade');
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
        Schema::dropIfExists('strategy_trade');
    }
};
