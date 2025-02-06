<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingAdditionalService extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'additional_service_id',
        'price'
    ];

    public function additionalService()
{
    return $this->belongsTo(AdditionalService::class, 'additional_service_id');
}

protected $hidden = [
    'password',
    'remember_token',
    'created_at',
    'updated_at',
];
}
