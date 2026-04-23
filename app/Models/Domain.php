<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Domain extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'domain',
        'status',
        'ssl_status',
        'is_primary',
        'verified_at'
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'is_primary'  => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
