<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Domain extends Model
{
    use HasFactory, HasUlids;
    public $incrementing = false;

    protected $keyType = 'string';

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
