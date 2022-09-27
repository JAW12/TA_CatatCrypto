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
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_asset');
            $table->foreignId('id_journal');
            $table->integer('status', false, true)->default(0)->comment('0 - pending, 1 - aktif, 2 - selesai');
            $table->integer('type')->comment('0 - short, 1 - long');
            $table->integer('leverage');
            $table->decimal('open_price', 19, 8, true)->default(0);
            $table->decimal('open_quantity', 19, 8)->unsigned()->default(0);
            $table->decimal('open_margin', 19, 8)->unsigned()->default(0);
            $table->dateTime('open_time');

            $table->decimal('average_price', 19, 8, true)->default(0);
            $table->decimal('margin', 19, 8)->unsigned()->default(0);
            $table->decimal('quantity_remaining', 19, 8)->unsigned()->default(0);


            $table->decimal('rr_expected', 5, 2, false)->nullbale();
            $table->decimal('real_rr', 5, 2, false)->nullable();

            $table->dateTime('close_time')->nullbale();
            $table->decimal('close_price', 19, 8, true)->default(0);
            $table->integer('diff_days')->nullable();
            $table->integer('diff_hours')->nullable();
            $table->integer('diff_minutes')->nullable();
            $table->integer('diff_seconds')->nullable();
            $table->decimal('pnl', 19, 2, false)->default(0);
            $table->decimal('total_fees', 19, 2, false)->default(0);
            $table->decimal('nett_pnl', 19, 2, false)->default(0);
            $table->decimal('roe', 19, 8, true)->default(0);
            $table->integer('wl')->comment('0 - loss, 1 - win')->nullable();
            $table->string('closed_at')->nullable();

            $table->text('notes')->nullable();
            $table->string('screenshot_url_1', 255)->nullable();
            $table->string('screenshot_url_2', 255)->nullable();
            $table->string('screenshot_url_3', 255)->nullable();
            $table->string('screenshot_url_4', 255)->nullable();

            $table->timestamps();
            $table->foreign('id_asset')->references('id')->on('assets');
            $table->foreign('id_journal')->references('id')->on('journals')->onDelete('cascade');
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
        Schema::dropIfExists('trades');
    }
};
