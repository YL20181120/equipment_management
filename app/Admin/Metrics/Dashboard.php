<?php

namespace App\Admin\Metrics;

use App\EquipmentDetail;

class Dashboard
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function title()
    {
        return view('admin::dashboard.title');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 临期设备
     */
    public static function checkEquipment()
    {
        $equipment = EquipmentDetail::query()
            ->take(20)
            ->orderBy('check_date')
            ->with('warehouse', 'equipment')
            ->where('is_in_stock', true)
            ->get();
        return view('metrics.check_equipment', compact('equipment'));
    }
}
