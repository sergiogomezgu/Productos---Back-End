<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{


protected $fillable = [
    'name',
    'city',
    'address',
    'phone',
    'email',
    'description',
    'zona',
    'comision_porcentaje',
];

public function reservations()
{
    return $this->hasMany(Reservation::class);
}
}
