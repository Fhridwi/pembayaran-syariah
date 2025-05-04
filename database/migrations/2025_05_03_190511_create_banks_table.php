<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanksTable extends Migration
{
    public function up()
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->char('id', 36)->primary(); // UUID
            $table->string('nama_bank', 100);
            $table->string('nomor_rekening', 50);
            $table->string('nama_pemilik', 100);
            $table->string('logo')->nullable(); // opsional, untuk logo bank
            $table->boolean('is_aktif')->default(true); // status aktif atau tidak
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('banks');
    }
}
