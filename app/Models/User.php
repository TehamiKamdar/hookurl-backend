<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
        'avatar',
        'status',
        'last_login_at',
        'password_attempts',
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at'     => 'datetime',
        'password'          => 'hashed',
        'status'            => 'boolean',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function links()
    {
        return $this->hasMany(Link::class);
    }

    public function domains()
    {
        return $this->hasMany(Domain::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function settings()
    {
        return $this->hasMany(Setting::class);
    }
}
