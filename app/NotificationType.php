<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationType extends Model
{
    public const UPCOMING_EVENTS = 'upcoming-events';
    public const GUEST_JOINED = 'guest-joined';

    protected $fillable = ['id', 'name', 'description'];

    public $timestamps = false;
    public $incrementing = false;

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps();
    }
}
