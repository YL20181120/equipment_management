<?php


namespace App\Traits;


use App\User;

trait HasUser
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
