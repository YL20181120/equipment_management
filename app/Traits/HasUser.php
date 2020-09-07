<?php


namespace App\Traits;


use Encore\Admin\Auth\Database\Administrator;

trait HasUser
{
    public function user()
    {
        return $this->belongsTo(Administrator::class, 'user_id');
    }
}
