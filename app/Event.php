<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['title', 'description', 'start_at', 'end_at'];

    protected $dates = ['start_at', 'end_at'];

    public function isOwnedBy(User $user): bool
    {
        $participant = $this->participants()->find($user->id);

        if (!is_a($participant, User::class)) {
            return false;
        }
        return $participant->pivot->owner;
    }

    public function participants()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('owner')
            ->withTimestamps();
    }
}
