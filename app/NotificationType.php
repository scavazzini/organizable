<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationType extends Model
{
    protected $fillable = ['id', 'name', 'description'];

    public $timestamps = false;
    public $incrementing = false;

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps();
    }
}
