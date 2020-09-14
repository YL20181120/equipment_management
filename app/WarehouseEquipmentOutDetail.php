<?php

namespace App;

use App\Traits\Date;
use App\Traits\HasEquipment;
use App\Traits\HasUser;
use App\Traits\HasWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseEquipmentOutDetail extends Model
{
    protected $table = 'warehouse_equipment_out_details';
    protected $fillable = [
        'warehouse_id',
        'user_id',
        'warehouse_equipment_out_id',
        'equipment_id',
        'stock_out',
        'rest',
        'equipment_detail_id'
    ];
    protected $dates = [
        'check_date'
    ];

    protected $casts = [
        'rest'
    ];

    public function warehouseEquipmentOut()
    {
        return $this->belongsTo(WarehouseEquipmentOut::class, 'warehouse_equipment_out_id');
    }

    public function equipmentDetail()
    {
        return $this->belongsTo(EquipmentDetail::class, 'equipment_detail_id');
    }

    use HasEquipment;
    use HasWarehouse;
    use HasUser;
    use Date;
    use SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $detail) {
            if (blank($detail->warehouse_id)) {
                $detail->warehouse_id = $detail->warehouseEquipmentOut->warehouse_id;
            }
            if (blank($detail->user_id)) {
                $detail->user_id = $detail->warehouseEquipmentOut->user_id;
            }
            // 检测设备出库数量是否足够
            $warehouse = WarehouseEquipment::query()
                ->where('warehouse_id', $detail->warehouse_id)
                ->where('equipment_id', $detail->equipment_id)
                ->first();
            $item      = $detail->equipment;
            if ($warehouse == null || $warehouse->stock < $detail->stock_out) {
                throw new \Exception('设备数量不足[hint:' . sprintf("%s-%s-%s", $item->name, $item->code, $item->model) . '].');
            }
        });

        static::created(function (self $detail) {
            /** @var Model $warehouseEquipment */
            $warehouseEquipment = WarehouseEquipment::firstOrCreate(['warehouse_id' => $detail->warehouse_id, 'equipment_id' => $detail->equipment_id]);
            $warehouseEquipment->decrement('stock', $detail->stock_out);
            $equipment = EquipmentDetail::query()->find($detail->equipment_detail_id);
            $equipment->update(['is_in_stock' => 0]);
        });
    }
}
