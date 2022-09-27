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
            $table->foreignId('id_asset');
            $table->foreignId('id_wallet');
            $table->decimal('amount', 19, 8)->unsigned()->default(0);
            $table->decimal('average_price', 19, 8, true)->default(0);
            $table->decimal('pnl', 19, 2, false)->default(0);
            $table->timestamps();
            $table->foreign('id_asset')->references('id')->on('assets');
            $table->foreign('id_wallet')->references('id')->on('wallets');
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
