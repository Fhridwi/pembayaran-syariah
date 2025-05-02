<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;

    protected $keyType = 'string'; 
    public $incrementing = false;

    protected $fillable = [
        'id',
        'santri_id',
        'tahun_id',
        'kategori_id',
        'bulan_tagihan',
        'jatuh_tempo',
        'status',
    ];

    /**
     * Relasi ke model Santri
     */
    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    /**
     * Relasi ke model TahunAjaran
     */
    public function tahun()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_id');
    }

    /**
     * Relasi ke model KategoriTagihan
     */
    public function kategori()
    {
        return $this->belongsTo(KategoriTagihan::class, 'kategori_id');
    }

    /**
     * Relasi ke pembayaran (jika dibutuhkan)
     */
    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class);
    }
}
