<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\CallType;
use App\Models\AlertTemplate;

class FlightSchedule extends Model
{
    protected $table = 'flight_schedules';
    protected $fillable = [
        'flight_number',
        'departure_airport',
        'arrival_airport',
        'departure_time',
        'arrival_time',
        'status',
        'caller_type_id',
        'user_id',
        'airplane_id',
        'alert_template_id',
        'ticket_number',
        'counter',
        'userdetails_id',
        'user_details_id',
    ];

    public function callerType()
    {
        return $this->belongsTo(CallType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function airplane()
    {
        return $this->belongsTo(AirPlane::class);
    }

    public function alertTemplate()
    {
        return $this->belongsTo(AlertTemplate::class);
    }

    public function userDetails()
    {
        return $this->belongsTo(UserDetails::class);
    }
}


