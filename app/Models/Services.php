<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{

    use HasFactory;
    protected $fillable = [
        'user_id',
        'category_id',
        'service_name',
        'service_details',
        'price',
        'service_images',
        'duration',
        'location',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'service_images' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function additionalServices()
    {
        return $this->hasMany(AdditionalService::class, 'service_id');
    }

    public function favoritedBy()
{
    return $this->belongsToMany(user::class, 'favorites', 'service_id', 'user_id')->withTimestamps();
}

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'service_id');
    }
public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
    ];
}
