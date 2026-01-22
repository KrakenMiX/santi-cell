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
        Schema::create('transaksi_topup', function (Blueprint $table) {
            $table->id();
            $table->string('ref_id')->unique();
            $table->string('buyer_sku_code');
            $table->string('customer_no');
            $table->string('product_name');
            $table->decimal('price', 10, 2);
            $table->string('status')->default('Pending');
            $table->string('message')->nullable();
            $table->string('sn')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_topup');
    }
};
