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
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 19, 2, true);
            $table->integer('duration_days')->comment('0 - no limit, others - limit');
            $table->integer('max_wallets')->default(0)->comment("-1 - no limit, 0 - can't make, others - limit");
            $table->integer('max_journals')->default(0)->comment("-1 - no limit, 0 - can't make, others - limit");
            $table->integer('trades_quantity_per_month')->comment("-1 - no limit, 0 - can't make, others - limit");
            $table->integer('enable_binance')->default(0)->commment("0 - can't, 1 - can");
            $table->integer('enable_notification')->default(0)->commment("0 - can't, 1 - can");
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
        Schema::dropIfExists('memberships');
    }
};
