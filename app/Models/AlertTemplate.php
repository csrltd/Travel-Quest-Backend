<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlertTemplate extends Model
{
    protected $table = 'alert_templates';
    protected $fillable = ['language', 'title', 'body', 'user_id', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
