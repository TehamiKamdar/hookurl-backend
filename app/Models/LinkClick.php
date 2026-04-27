<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkClick extends Model
{
    use HasFactory;

    protected $fillable = [
        'link_id',
        'ip_address',
        'country',
        'city',
        'device_type',
        'browser',
        'os',
        'referer',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'clicked_at'
    ];

    protected $casts = [
        'clicked_at' => 'datetime'
    ];

    public function link()
    {
        return $this->belongsTo(Link::class);
    }
}
