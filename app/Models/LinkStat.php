<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LinkStat extends Model
{
    use HasFactory;

    protected $table = 'link_stats_daily';

    protected $fillable = [
        'link_id',
        'date',
        'total_clicks',
        'unique_clicks',
        'mobile_clicks',
        'desktop_clicks',
        'tablet_clicks'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function link()
    {
        return $this->belongsTo(Link::class);
    }
}
