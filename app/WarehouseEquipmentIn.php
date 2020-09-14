<?php

namespace App;

use App\Traits\Date;
use App\Traits\HasUser;
use App\Traits\HasWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseEquipmentIn extends Model
{
    protected $fillable = [
        'user_id',
        'warehouse_id',
        'type'
    ];
    use HasWarehouse;
    use HasUser;
    use Date;
    use SoftDeletes;
    const TYPE_OPTIONS = [
        '自购' => '自购',
        '调拨' => '调拨',
        '归还' => '归还',
        '其他' => '其他',
    ];

    public function items()
    {
        return $this->hasMany(WarehouseEquipmentInDetail::class, 'warehouse_equipment_in_id');
    }

    public function equipment()
    {
        return $this->hasMany(EquipmentDetail::class, 'equipment_in_id');
    }
}
