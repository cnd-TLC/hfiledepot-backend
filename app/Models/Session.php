<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'device_name', 'ip_address', 'user_agent', 'session_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
