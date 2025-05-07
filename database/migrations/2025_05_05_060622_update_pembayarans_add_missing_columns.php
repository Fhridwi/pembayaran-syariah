<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            if (!Schema::hasColumn('pembayarans', 'nomor_pembayaran')) {
                $table->string('nomor_pembayaran', 50)->unique()->after('id');
            }

            if (!Schema::hasColumn('pembayarans', 'user_id')) {
                $table->char('user_id', 36)->nullable()->index()->after('santri_id');
            }

            if (!Schema::hasColumn('pembayarans', 'metode_pembayaran')) {
                $table->enum('metode_pembayaran', ['tunai', 'transfer'])->after('tanggal_bayar');
            }

            if (!Schema::hasColumn('pembayarans', 'bank_pengirim')) {
                $table->string('bank_pengirim', 100)->nullable()->after('metode_pembayaran');
            }

            if (!Schema::hasColumn('pembayarans', 'nama_pengirim')) {
                $table->string('nama_pengirim', 100)->nullable()->after('bank_pengirim');
            }

            if (!Schema::hasColumn('pembayarans', 'keterangan_status')) {
                $table->text('keterangan_status')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropColumn([
                'nomor_pembayaran',
                'user_id',
                'metode_pembayaran',
                'bank_pengirim',
                'nama_pengirim',
                'keterangan_status',
            ]);
        });
    }
};
