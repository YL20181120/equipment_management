<?php

namespace App;

use App\Traits\Date;
use App\Traits\HasUser;
use App\Traits\HasWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    use SoftDeletes;

    public function items()
    {
        return $this->hasMany(WarehouseEquipmentOutDetail::class, 'warehouse_equipment_out_id');
    }
}
