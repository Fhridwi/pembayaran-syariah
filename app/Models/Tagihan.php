<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

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
