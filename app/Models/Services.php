<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{

    use HasFactory;
    protected $fillable = [
        'id',
        'category_id',
        'name',
        'description',
        'price',
        'image',
        'duration',
    ];


    public function category()
    {
        return $this->belongsTo(Categorie::class);
    }
    protected $casts = [
       'image' => 'array',
    ];

    public function favoritedBy()
{
    return $this->belongsToMany(ApiUser::class, 'favorites', 'service_id', 'user_id')->withTimestamps();
}
}
