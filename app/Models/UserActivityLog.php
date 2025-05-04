<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class UserActivityLog extends Model
{
    use HasUuids;

    protected $table = 'user_activity_logs';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'method',
        'url',
        'route_name',
        'action',
        'payload',
        'response_status'
    ];

    protected $casts = [
        'payload' => 'array',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope untuk filter
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, fn($query, $search) => 
            $query->where(function($query) use ($search) {
                $query->where('url', 'like', '%'.$search.'%')
                    ->orWhere('route_name', 'like', '%'.$search.'%')
                    ->orWhere('action', 'like', '%'.$search.'%');
            })
        );

        $query->when($filters['user'] ?? false, fn($query, $user) => 
            $query->whereHas('user', fn($query) => 
                $query->where('name', 'like', '%'.$user.'%')
            )
        );

        $query->when($filters['method'] ?? false, fn($query, $method) => 
            $query->where('method', $method)
        );
    }
}