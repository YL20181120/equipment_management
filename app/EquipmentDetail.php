<?php

namespace App;

use App\Traits\Date;
use App\Traits\HasEquipment;
use App\Traits\HasWarehouse;
use Illuminate\Database\Eloquent\Model;

class EquipmentDetail extends Model
{
    use Date;
    protected $fillable = [
        'equipment_id',
        'warehouse_id',
        'price',
        'check_date'
    ];
    protected $dates = [
        'check_date'
    ];

    use HasEquipment, HasWarehouse;
}
