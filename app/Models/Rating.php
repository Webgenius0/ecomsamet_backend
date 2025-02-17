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
      return $this->belongsTo(user::class);
   }

   public function service(){
     return $this->belongsTo(Services::class,'service_id');
   }


   protected $hidden = [
    'password',
    'remember_token',
    'created_at',
    'updated_at',
];
}
