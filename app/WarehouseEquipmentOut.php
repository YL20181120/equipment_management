<?php

namespace App;

use App\Traits\HasUser;
use App\Traits\HasWarehouse;
use Illuminate\Database\Eloquent\Model;

class WarehouseEquipmentOut extends Model
{
    protected $fillable = [
        'user_id',
        'warehouse_id'
    ];
    use HasWarehouse;
    use HasUser;

    public function items()
    {
        return $this->hasMany(WarehouseEquipmentOut::class, 'warehouse_equipment_out_id');
    }
}
