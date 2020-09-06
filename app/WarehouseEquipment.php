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

    use HasWarehouse;
    use HasEquipment;

    public function items()
    {
        return $this->hasMany(WarehouseEquipmentInDetail::class, 'warehouse_equipment_in_id');
    }
}
