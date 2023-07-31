<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    protected $table = 'user_details';

    protected $fillable = [
        'user_id',
        'firstName',
        'lastName',
        'address',
        'dob',
        'status',
        'mobileTelephone',
        'workTelephone',
        'gender',
        'nationality',
        'country',
        'nid',
        'passport',
        'language',
        'placeOfIssue',
        'workEmail',
        'userType',
        
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
