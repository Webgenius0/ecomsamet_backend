<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','service_id', 'status'];
    protected $table = 'favorites';





    public function service(){
        return $this->belongsTo(Services::class,'service_id');
    }
    public function apiuser(){
        return $this->belongsTo(ApiUser::class, 'user_id');
    }

    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
    ];
}
