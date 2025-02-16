<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
   use HasFactory;

   protected $fillable = ['user_id', 'service_id', 'rating', 'comment'];

   public function user()
   {
      return $this->belongsTo(ApiUser::class);
   }

   public function service(){
     return $this->belongsTo(Services::class);
   }
}
