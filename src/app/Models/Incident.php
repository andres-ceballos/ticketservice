<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'incident_status', 'service_rating', 'notification_user', 'notification_tech', 'user_id', 'tech_id'];
}
