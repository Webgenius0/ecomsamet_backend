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
     'date',
     'time',
     'status',
   ];

   public function apiuser()
   {
    return $this->belongsTo(ApiUser::class, 'user_id', 'id');

   }

   public function service()
   {
     return $this->belongsTo(Services::class);
   }
}
