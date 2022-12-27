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
            $table->foreignId('user_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('pnl', 19, 2, true)->default();
            $table->decimal('balances', 19, 8, true)->default(0);
            $table->integer('risk', false, true)->nullable();
            $table->bigInteger('count_of_trades')->default(0);
            $table->decimal('winrate', 5, 4, true);
            $table->decimal('target', 19, 8, true)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
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
