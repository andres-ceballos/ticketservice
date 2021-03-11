<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailIncident extends Model
{
    use HasFactory;

    protected $fillable = ['message_reply', 'from_user_id', 'incident_id'];

    protected $casts = [
        'created_at' => 'datetime:h:i:s a',
        'updated_at' => 'datetime:h:i:s a'
    ];
}
