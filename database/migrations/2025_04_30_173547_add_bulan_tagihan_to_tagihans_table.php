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
    Schema::table('tagihans', function (Blueprint $table) {
        $table->string('bulan_tagihan', 20)->after('kategori_id');
    });
}

public function down(): void
{
    Schema::table('tagihans', function (Blueprint $table) {
        $table->dropColumn('bulan_tagihan');
    });
}

};
