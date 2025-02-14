<?php

namespace App\Models;

class EventParticipant extends BaseModel
{
    public static $cacheKey = 'event_participants';

    protected $fillable = [
        'event_id',
        'user_id',
    ];
}
