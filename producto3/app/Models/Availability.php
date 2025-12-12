<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
  protected $fillable = ['hotel_id', 'date', 'available'];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
