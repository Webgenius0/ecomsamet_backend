<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ApiUser extends Model implements JWTSubject
{
    use HasFactory;

    protected $fillable = [
        'fullname',
        'phone',
        'email',
        'password',
        'image',
        'provider_id',
        'mobile_verfi_otp',
    ];

    protected $hidden = [
        'password', // Hide the password when returning JSON responses
    ];

    // Method to get the unique identifier for the user (e.g., user ID)
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    // Method to get custom claims for the JWT token
    public function getJWTCustomClaims()
    {
        return [];
    }

    // Define the method required by Laravel's Auth system
    public function getAuthIdentifierName()
    {
        return 'id';  // Return the name of the primary key column
    }

    public function favorites()
{
    return $this->hasMany(Favorite::class);
}


public function favoritedServices()
{
    return $this->belongsToMany(Services::class, 'favorites', 'user_id', 'service_id')->withTimestamps();
}
}
