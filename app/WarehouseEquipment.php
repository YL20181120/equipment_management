<?php

namespace App;

use App\Traits\Date;
use App\Traits\HasEquipment;
use App\Traits\HasWarehouse;
use Illuminate\Database\Eloquent\Model;

class WarehouseEquipment extends Model
{
    use Date;
    protected $table = 'warehouse_equipments';
    protected $fillable = [
        'equipment_id',
        'warehouse_id',
        'stock',
    ];

    protected $attributes = [
        'stock' => 0
    ];
    use HasWarehouse;
    use HasEquipment;
}
