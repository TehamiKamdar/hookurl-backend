<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'billing_cycle',
        'max_links',
        'max_custom_domains',
        'analytics_retention_days',
        'qr_codes',
        'custom_alias',
        'is_active'
    ];

    protected $casts = [
        'price'       => 'decimal:2',
        'qr_codes'    => 'boolean',
        'custom_alias'=> 'boolean',
        'is_active'   => 'boolean',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
