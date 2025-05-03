<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kategori_tagihans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_kategori')->unique();
            $table->decimal('nominal');
            $table->enum('jenis_tagihan', ['bulanan', 'bebas']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_tagihans');
    }
};
