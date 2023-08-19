<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Message extends Model
{
    protected $table = 'messages'; // Set the table name to 'notifications'
    protected $fillable = ['phone_number', 'doneBy', 'message']; // Define fillable fields


    
}
