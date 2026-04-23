<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QrCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'link_id',
        'style_json',
        'image_path',
        'downloads'
    ];

    protected $casts = [
        'style_json' => 'array'
    ];

    public function link()
    {
        return $this->belongsTo(Link::class);
    }
}
