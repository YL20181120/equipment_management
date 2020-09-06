<?php

namespace App;

use App\Traits\Date;
use App\Traits\HasUser;
use App\Traits\HasWarehouse;
use Illuminate\Database\Eloquent\Model;

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
    const TYPE_OPTIONS = [
        '1' => '自购',
        '2' => '调拨',
        '-1' => '其他'
    ];
}
