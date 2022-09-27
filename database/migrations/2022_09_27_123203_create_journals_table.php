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
        Schema::create('journals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('pnl', 19, 2, true)->default();
            $table->decimal('balances', 19, 8, true)->default(0);
            $table->integer('risk', false, true)->nullable();
            $table->bigInteger('count_of_trades')->default(0);
            $table->decimal('winrate', 5, 4, true);
            $table->decimal('target', 19, 8, true)->nullable();
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users');
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('journals');
    }
};
