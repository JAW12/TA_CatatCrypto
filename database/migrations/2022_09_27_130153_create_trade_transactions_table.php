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
        Schema::create('trade_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_trade');

            $table->integer('type')->comment('0 - entry, 1 - close');
            $table->decimal('price', 19, 8, true);
            $table->decimal('quantity', 19, 8, false);
            $table->decimal('fee', 19, 8, true)->nullable();
            $table->decimal('pnl', 19, 2, false)->default(0);
            $table->dateTime('time')->nullable();
            $table->timestamps();

            $table->foreign('id_trade')->references('id')->on('trades');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trade_transactions');
    }
};
