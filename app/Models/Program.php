<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Program extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nama_program',
        'keterangan'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function($model) {
            $model->id = (string) Str::uuid();
        });
    }

}
