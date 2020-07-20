<?php

namespace App;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use UUID;

    protected $fillable = ['title', 'description', 'start_at', 'end_at'];

    protected $dates = ['start_at', 'end_at'];

    public function isOwnedBy(User $user): bool
    {
        $guest = $this->guests()->find($user->id);

        if (!is_a($guest, User::class)) {
            return false;
        }
        return $guest->pivot->owner;
    }

    public function guests()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('owner')
            ->withTimestamps();
    }
}
