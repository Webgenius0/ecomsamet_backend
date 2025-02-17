<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description'];

    public function services()
    {
        return $this->hasMany(Services::class, 'category_id');
    }
    public function ratings(){
        return $this->hasMany(Rating::class, 'category_id');
    }

    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
    ];
}
