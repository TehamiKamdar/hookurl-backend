<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Link extends Model
{
    use HasFactory, HasUlids;
    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'guest_id',
        'title',
        'original_url',
        'short_code',
        'custom_alias',
        'password',
        'expires_at',
        'is_active',
        'clicks_count',
        'last_clicked_at'
    ];

    protected $casts = [
        'expires_at'      => 'datetime',
        'last_clicked_at' => 'datetime',
        'is_active'       => 'boolean',
    ];

    protected $hidden = [
        'password'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function clickLogs()
    {
        return $this->hasMany(ClickLog::class);
    }

    public function stats()
    {
        return $this->hasMany(LinkStat::class);
    }

    public function qrCode()
    {
        return $this->hasOne(QrCode::class);
    }
}
