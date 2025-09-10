<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

/* use App\Models\Rol; */

//use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
        'profile_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

     public function getJWTCustomClaims()
    {
        return [];
    }

    /* public function rol()
    {
        return $this->belongsToMany(Rol::class, 'roles_usuarios', 'id_user', 'id_rol');
    } */

    /* public function Empleador()
    {
        return $this->hasOne(Empleador::class);
    }

    public function Trabajador()
    {
        return $this->hasOne(Trabajador::class);
    } */

    /* public function posts()
    {
        return $this->hasMany(Post::class, 'id_autor');
    } */

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function digitalCards(): HasMany
    {
        return $this->hasMany(DigitalCard::class);
    }

    public function activeDigitalCard(): HasOne
    {
        return $this->hasOne(DigitalCard::class)->where('is_active', true);
    }
}
