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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('gender')->comment('m - male, f - female')->nullable();
            $table->dateTime('birthdate')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('membership_status')->default(0)->comment('0 - not registered, 1 - basic, 2 - home, 3 - professional, 4 - business');
            $table->integer('max_wallets')->default(0)->comment("-1 - no limit, 0 - can't make, others - limit");
            $table->integer('max_journals')->default(0)->comment("-1 - no limit, 0 - can't make, others - limit");
            $table->integer('trades_quantity_per_month')->comment("-1 - no limit, 0 - can't make, others - limit");
            $table->integer('remaining_trades')->default(0);
            $table->integer('enable_binance')->default(0)->commment("0 - can't, 1 - can");
            $table->integer('enable_notification')->default(0)->commment("0 - can't, 1 - can");
            $table->dateTime('membership_since')->nullable();
            $table->dateTime('membership_till')->nullable();
            $table->decimal('spent', 19, 2, true);
            $table->rememberToken();
            $table->timestamps();
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
        Schema::dropIfExists('users');
    }
};
