<?php

namespace App;

use App\Traits\Date;
use App\Traits\HasEquipment;
use App\Traits\HasUser;
use App\Traits\HasWarehouse;
use Illuminate\Database\Eloquent\Model;

class WarehouseEquipmentInDetail extends Model
{
    protected $fillable = [
        'warehouse_id',
        'user_id',
        'warehouse_equipment_in_id',
        'equipment_id',
        'stock_in',
        'check_date',
    ];
    protected $dates = [
        'check_date'
    ];
    use HasEquipment;
    use HasWarehouse;
    use HasUser;
    use Date;
}
