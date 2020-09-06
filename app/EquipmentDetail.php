<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EquipmentDetail extends Model
{
    protected $fillable = [
        'equipment_id',
        'warehouse_id',
        'price',
        'check_date'
    ];
    protected $dates = [
        'check_date'
    ];
}
