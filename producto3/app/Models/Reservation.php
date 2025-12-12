<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'hotel_id',
        'hotel_user_id',
        'check_in',
        'check_out',
        'guests',
        'total_price',
        'status',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function hotelUser()
    {
        return $this->belongsTo(HotelUser::class);
    }
}
