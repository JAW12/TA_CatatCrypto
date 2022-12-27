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
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone_number')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('user_type')->default('trial');
            $table->string('status')->default('active');
            $table->string('gender')->comment('m - male, f - female')->nullable();
            $table->date('birthdate')->nullable();
            // $table->integer('membership_status')->default(0)->comment('0 - not registered, 1 - basic, 2 - home, 3 - professional, 4 - business');
            $table->integer('max_wallets')->default(1)->comment("-1 - no limit, 0 - can't make, others - limit");
            $table->integer('max_journals')->default(1)->comment("-1 - no limit, 0 - can't make, others - limit");
            $table->integer('trades_quantity_per_month')->default(100)->comment("-1 - no limit, 0 - can't make, others - limit");
            $table->integer('remaining_trades')->default(100);
            // $table->integer('enable_binance')->default(0)->commment("0 - can't, 1 - can");
            // $table->integer('enable_notification')->default(0)->commment("0 - can't, 1 - can");
            $table->timestamp('membership_since')->nullable();
            $table->timestamp('membership_till')->nullable();
            $table->decimal('spent', 19, 2, true)->default(0);
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
