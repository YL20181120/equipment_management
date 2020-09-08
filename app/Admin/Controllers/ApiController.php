<?php


namespace App\Admin\Controllers;


use App\EquipmentDetail;
use App\Http\Controllers\Controller;
use App\WarehouseEquipment;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function equipment(Request $request)
    {
        $id   = $request->get('q');
        $data = EquipmentDetail::query()
            ->where('equipment_id', $id)
            ->where('is_in_stock', 1)
            ->with('equipment')->get()->transform(function ($detail) {
                $item = $detail->equipment;
                return [
                    'id' => $detail->id,
                    'label' => sprintf("%s-%s-%s-%s", $detail->id, $item->name, $item->code, $item->model)
                ];
            })->pluck('label', 'id');
        return $this->response($data);
    }

    public function warehouse(Request $request)
    {
        $id   = $request->get('q');
        $data = WarehouseEquipment::query()
            ->where('warehouse_id', $id)
            ->with('equipment')->get()->transform(function ($detail) {
                $item = $detail->equipment;
                return [
                    'id' => $item->id,
                    'label' => sprintf("%s-%s-%s-%s", $item->id, $item->name, $item->code, $item->model)
                ];
            })->pluck('label', 'id');
        return $this->response($data);
    }
}
