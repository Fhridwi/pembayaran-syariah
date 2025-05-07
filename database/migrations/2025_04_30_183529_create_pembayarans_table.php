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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nomor_pembayaran', 50)->unique();
            $table->uuid('tagihan_id');
            $table->uuid('santri_id')->nullable();
            $table->uuid('user_id');
            $table->uuid('penerima_id')->nullable();
            $table->decimal('nominal_bayar', 8, 2);
            $table->date('tanggal_bayar');
            $table->enum('metode_pembayaran', ['tunai', 'transfer']);
            $table->string('bank_pengirim', 100)->nullable();
            $table->string('nama_pengirim', 100)->nullable();
            $table->string('bukti_bayar', 255)->nullable();
            $table->enum('status', ['pending', 'tolak', 'diterima']);
            $table->text('keterangan_status')->nullable();
            $table->timestamps();
        
            // Foreign keys
            $table->foreign('tagihan_id')->references('id')->on('tagihans')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('santri_id')->references('id')->on('santris')->onDelete('set null');
            $table->foreign('penerima_id')->references('id')->on('users')->onDelete('set null');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
