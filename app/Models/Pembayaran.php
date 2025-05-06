<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayarans';
    protected $keyType = 'string';
    public $incrementing = false;

   
protected $fillable = [
    'id',
    'nomor_pembayaran',
    'tagihan_id',
    'santri_id',
    'user_id',
    'penerima_id',
    'nominal_bayar',
    'tanggal_bayar',
    'metode_pembayaran',
    'bank_pengirim',
    'nama_pengirim',
    'bukti_bayar',
    'status',
    'keterangan_status',
];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid(); // Generate UUID untuk ID
        });
    }

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

    // app/Models/Pembayaran.php

    public function santri()
    {
        return $this->belongsTo(Santri::class, 'santri_id', 'id');
    }
}
