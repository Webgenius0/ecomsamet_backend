<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
   use HasFactory;

   protected $fillable = [
     'user_id',
     'service_id',
     'booking_date',
     'booking_time',
     'total_price',
   ];

   //

   public function user()
   {
    return $this->belongsTo(User::class, 'user_id');

   }

   public function service()
   {
     return $this->belongsTo(Services::class);
   }
   public function additionalServices()
{
    return $this->hasMany(BookingAdditionalService::class, 'booking_id');
}
protected $hidden = [
    'password',
    'remember_token',
    'created_at',
    'updated_at',
];

protected function casts(): array
   {
       return [
           'total_price' => 'float',
       ];
   }
}
