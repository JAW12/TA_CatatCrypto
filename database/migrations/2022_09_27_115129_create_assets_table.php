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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('coin_gecko_id', 255)->unique();
            $table->string('symbol', 255);
            $table->string('binance_symbol', 255)->unique()->nullable();
            $table->string('name', 255);
            $table->json('platforms');
            $table->string('exchanges', 255)->nullable();
            $table->string('special_targets', 255)->nullable();
            $table->json('links');
            $table->integer('market_cap_rank')->nullable();
            $table->bigInteger('market_cap', false, false);
            $table->bigInteger('total_volume', false, false);
            $table->bigInteger('market_cap_24h', false, false)->nullable();
            $table->bigInteger('total_supply', false, false)->nullable();
            $table->bigInteger('circulating_supply', false, false);
            $table->decimal('current_price', 19, 8, true);
            $table->string('thumb', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assets');
    }
};
