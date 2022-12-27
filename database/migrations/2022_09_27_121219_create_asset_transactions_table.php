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
        Schema::create('asset_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_wallet_id');
            $table->string('trade_id', 255)->nullable();
            $table->string('order_id', 255)->nullable();
            $table->integer('type')->comment('0 - buy, 1 - sell, 2 - transfer out, 3 - transfer in');
            $table->decimal('price', 19, 8, true);
            $table->decimal('amount', 19, 8, false);
            $table->decimal('fee', 19, 8, true)->nullable();
            $table->integer('status')->comment('0 - pending, 1 - filled');
            $table->integer('integrated')->comment('0 - manual, 1 - integrated');
            $table->dateTime('time')->nullable();
            $table->timestamps();

            $table->foreign('asset_wallet_id')->references('id')->on('asset_wallet')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asset_transactions');
    }
};
