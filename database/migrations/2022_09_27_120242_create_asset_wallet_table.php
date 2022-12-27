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
        Schema::create('asset_wallet', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id');
            $table->foreignId('wallet_id');
            $table->decimal('amount', 19, 8)->unsigned()->default(0);
            $table->decimal('average_price', 19, 8, true)->default(0);
            $table->decimal('pnl', 19, 2, false)->default(0);
            $table->timestamps();
            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('CASCADE');
            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asset_wallet');
    }
};
