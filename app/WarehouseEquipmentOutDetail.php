<?php

namespace App;

use App\Traits\Date;
use App\Traits\HasEquipment;
use App\Traits\HasUser;
use App\Traits\HasWarehouse;
use Illuminate\Database\Eloquent\Model;

class WarehouseEquipmentOutDetail extends Model
{
    protected $fillable = [
        'warehouse_id',
        'user_id',
        'warehouse_equipment_out_id',
        'equipment_id',
        'stock_out',
        'check_date',
        'rest'
    ];
    protected $dates = [
        'check_date'
    ];

    protected $casts = [
        'rest'
    ];

    use HasEquipment;
    use HasWarehouse;
    use HasUser;
    use Date;
}
