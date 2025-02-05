<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalService extends Model
{
    Use HasFactory;
    protected $fillable = [
        'service_id',
        'name',
        'description',
        'price',
        'images',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function service(){
        return $this->belongsTo(Services::class, 'service_id');
    }
}
