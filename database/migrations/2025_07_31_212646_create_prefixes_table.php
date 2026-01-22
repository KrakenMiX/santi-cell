<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('prefixes', function (Blueprint $table) {
            $table->id();
            $table->string('prefix', 5); // contoh: 0811, 0858
            $table->string('operator'); // Telkomsel, Indosat, dll
            $table->string('keterangan')->nullable(); // keterangan tambahan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prefixes');
    }
};