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
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('category');
            $table->string('brand');
            $table->string('type');
            $table->string('seller_name');
            $table->decimal('price', 10, 2);
            $table->string('buyer_sku_code');
            $table->boolean('buyer_product_status');
            $table->boolean('seller_product_status');
            $table->boolean('unlimited_stock');
            $table->integer('stock')->nullable();
            $table->boolean('multi');
            $table->string('start_cut_off')->nullable();
            $table->string('end_cut_off')->nullable();
            $table->text('desc')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};
