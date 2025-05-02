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
        Schema::create('santris', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('nis')->unique();
            $table->string('nama');
            $table->enum('jenis_kelamin', ['L','P'])->default('L');
            $table->string('tempat_lahir', 20);
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->string('program');
            $table->string('angkatan');
            $table->string('sekolah');
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('santris');
    }
};
