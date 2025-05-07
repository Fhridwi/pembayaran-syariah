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
        Schema::create('tagihans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('santri_id');
            $table->foreign('santri_id')->references('id')->on('santris')->onDelete('cascade');
            $table->uuid('tahun_id');
            $table->foreign('tahun_id')->references('id')->on('tahun_ajarans')->onDelete('cascade');
            $table->uuid('kategori_id');
            $table->foreign('kategori_id')->references('id')->on('kategori_tagihans')->onDelete('cascade');
            $table->date('jatuh_tempo');
            $table->enum('status', ['belum', 'lunas','diproses'])->default('belum');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihans');
    }
};
