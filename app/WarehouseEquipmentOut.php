<?php

namespace App;

use App\Traits\Date;
use App\Traits\HasUser;
use App\Traits\HasWarehouse;
use Illuminate\Database\Eloquent\Model;

class WarehouseEquipmentOut extends Model
{
    protected $fillable = [
        'user_id',
        'warehouse_id',
        'use_name'
    ];
    use HasWarehouse;
    use HasUser;
    use Date;

    public function items()
    {
        return $this->hasMany(WarehouseEquipmentOut::class, 'warehouse_equipment_out_id');
    }
}
