<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('prices_game', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();           // code dari VIPayment
            $table->string('game');                     // nama game (ex: Mobile Legends A)
            $table->string('name');                     // nama paket produk (ex: 14 Diamonds)
            $table->integer('price_basic');
            $table->integer('price_premium');
            $table->integer('price_special');
            $table->string('server');
            $table->string('status');                   // available / empty
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prices_game');
    }
};
