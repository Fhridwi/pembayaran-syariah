<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Santri extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'nis',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'program',
        'angkatan',
        'sekolah',
        'foto',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tagihans()
    {
        return $this->hasMany(Tagihan::class, 'santri_id', 'id');
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function programs()
    {
        return $this->belongsTo(\App\Models\Program::class);
    }
}
