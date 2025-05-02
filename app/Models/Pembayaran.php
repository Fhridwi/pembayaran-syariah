<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayarans';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'tagihan_id',
        'user_id',
        'nominal_bayar',
        'tanggal_bayar',
        'bukti_bayar',
        'status',
    ];

    /**
     * Relasi ke Tagihan
     */
    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class);
    }

    /**
     * Relasi ke User (wali/admin yang membayar)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
