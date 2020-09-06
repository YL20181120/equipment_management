<?php

namespace App;

use App\Traits\HasUser;
use App\Traits\HasWarehouse;
use Illuminate\Database\Eloquent\Model;

class WarehouseEquipmentIn extends Model
{
    protected $fillable = [
        'user_id',
        'warehouse_id'
    ];
    use HasWarehouse;
    use HasUser;
}
