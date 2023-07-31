<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class AirPlane extends Model
{
    protected $table = 'airplanes';
    protected $fillable = ['name', 'airplaneCode', 'user_id', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function flightSchedules()
    {
        return $this->hasMany(FlightSchedule::class);
    }
}

