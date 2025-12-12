<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class HotelUser extends Authenticatable
{
    protected $fillable = [
        'hotel_id',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
