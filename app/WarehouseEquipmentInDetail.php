<?php

namespace App;

use App\Traits\Date;
use App\Traits\HasEquipment;
use App\Traits\HasUser;
use App\Traits\HasWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseEquipmentInDetail extends Model
{
    protected $fillable = [
        'warehouse_id',
        'user_id',
        'warehouse_equipment_in_id',
        'equipment_id',
        'stock_in',
        'check_date',
        'rest'
    ];
    protected $dates = [
        'check_date'
    ];

    protected $casts = [
        'rest' => 'array'
    ];
    use HasEquipment;
    use HasWarehouse;
    use HasUser;
    use Date;
    use SoftDeletes;

    public function warehouseEquipmentIn()
    {
        return $this->belongsTo(WarehouseEquipmentIn::class, 'warehouse_equipment_in_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $detail) {
            if (blank($detail->warehouse_id)) {
                $detail->warehouse_id = $detail->warehouseEquipmentIn->warehouse_id;
            }
            if (blank($detail->user_id)) {
                $detail->user_id = $detail->warehouseEquipmentIn->user_id;
            }
        });
        static::created(function (self $detail) {
            $warehouseEquipment = WarehouseEquipment::firstOrCreate(['warehouse_id' => $detail->warehouse_id, 'equipment_id' => $detail->equipment_id]);
            $warehouseEquipment->increment('stock', $detail->stock_in);
            if (blank($detail->rest['in_ids'])) {
                // 按设备数量创建设备明细
                for ($i = 1; $i <= $detail->stock_in; $i++) {
                    EquipmentDetail::create([
                        'equipment_id' => $detail->equipment_id,
                        'warehouse_id' => $detail->warehouse_id,
                        'price' => $detail->equipment->price,
                        'check_date' => $detail->check_date,
                        'equipment_in_id' => $detail->warehouse_equipment_in_id
                    ]);
                }
            }
        });
    }
}
