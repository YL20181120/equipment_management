<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    use SoftDeletes;
    protected $table = 'equipments';

    const CATEGORY = [
        '1' => '自动站',
        '2' => '探空站',
        '0' => '其他'
    ];
}
