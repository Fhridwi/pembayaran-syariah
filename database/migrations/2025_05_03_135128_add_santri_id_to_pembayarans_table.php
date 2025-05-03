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
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->char('santri_id', 36)->after('tagihan_id')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropColumn('santri_id');
        });
    }
    
};
