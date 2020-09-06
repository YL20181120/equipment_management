<?php

namespace App;

use App\Traits\HasEquipment;
use App\Traits\HasWarehouse;
use Illuminate\Database\Eloquent\Model;

class WarehouseEquipment extends Model
{
    protected $fillable = [
        'equipment_id',
        'warehouse_id',
        'stock',
    ];

    use HasWarehouse;
    use HasEquipment;

    public function items()
    {
        return $this->hasMany(WarehouseEquipmentInDetail::class, 'warehouse_equipment_in_id');
    }
}
