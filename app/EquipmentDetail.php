<?php

namespace App;

use App\Traits\Date;
use App\Traits\HasEquipment;
use App\Traits\HasWarehouse;
use Illuminate\Database\Eloquent\Model;
use Milon\Barcode\Facades\DNS2DFacade;

class EquipmentDetail extends Model
{
    use Date;
    protected $fillable = [
        'equipment_id',
        'warehouse_id',
        'price',
        'check_date',
        'is_in_stock',
        'equipment_in_id'
    ];
    protected $dates = [
        'check_date'
    ];

    protected $casts = [
        'is_in_stock' => 'boolean'
    ];

    public function equipmentIn()
    {
        return $this->belongsTo(WarehouseEquipmentIn::class, 'equipment_in_id');
    }

    use HasEquipment, HasWarehouse;

    public function getIsCheckAttribute()
    {
        return now()->gt($this->check_date);
    }

    public function getTagAttribute()
    {
        return DNS2DFacade::getBarcodeSVG($this->getKey(), 'PDF417', 2, 1);
    }
}
