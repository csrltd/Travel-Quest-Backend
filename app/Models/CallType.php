<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CallType extends Model
{
    protected $table = 'call_types';
    protected $fillable = ['name', 'user_id', 'type', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
