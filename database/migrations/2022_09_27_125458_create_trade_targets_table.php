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
        Schema::create('trade_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_trade');
            $table->integer('type')->commment('0 - sl, 1 - tp');
            $table->decimal('price', 19, 8, true);
            $table->decimal('pnl', 19, 8, true);
            $table->decimal('roe', 19, 8, true);
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
        Schema::dropIfExists('trade_targets');
    }
};
