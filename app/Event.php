<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['title', 'description', 'start_at', 'end_at'];

    public function participants()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('owner')
            ->withTimestamps();
    }
}
