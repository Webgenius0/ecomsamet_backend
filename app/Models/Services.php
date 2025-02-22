<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
        return $this->hasMany(Favorite::class, 'service_id');
    }
    public function getFavoriteStatusAttribute()
    {
        $userId = Auth::id();
        $isFavorited = $this->favoritedBy->contains('user_id', $userId);
        return $isFavorited ? true : false;
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
        'duration',
    ];

    protected function casts(): array
   {
       return [
           'price' => 'float',
       ];
   }

}
