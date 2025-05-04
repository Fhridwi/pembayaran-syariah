<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Bank extends Model
{
    protected $table = 'banks';
    public $incrementing = false;
    protected $keyType = 'char';

    protected $fillable = [
        'id',
        'nama_bank',
        'nomor_rekening',
        'nama_pemilik',
        'logo',
        'is_aktif',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }
}
