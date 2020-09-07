<?php

namespace App\Admin\Controllers;

use App\Equipment;
use App\Warehouse;
use App\WarehouseEquipmentOutDetail;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Grid;

class WarehouseEquipmentOutDetailController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '出库记录';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new WarehouseEquipmentOutDetail());
        $grid->model()->with('warehouse', 'user', 'equipment', 'warehouseEquipmentOut')->latest();
        $grid->column('id', __('Id'));
        $grid->column('warehouse_equipment_out_id', '入库单号')->display(function ($val) {
            return '<a href="' . route('admin.warehouse-equipment-outs.show', $val) . '">' . $val . '</a>';
        });
        $grid->column('warehouse.name', '仓库');
        $grid->column('equipment.name', '设备名称');
        $grid->column('equipment.code', '设备编码');
        $grid->column('equipment.model', '设备规格');
        $grid->column('stock_out', '出库数量');
        $grid->column('created_at', '出库时间');
        $grid->column('user.name', '操作员');
        $grid->disableCreateButton();
        $grid->disableActions();
        $grid->disableColumnSelector();
        $grid->disableRowSelector();
        $grid->filter(function (Grid\Filter $filter) {
            $filter->disableIdFilter();
            $filter->equal('warehouse_id', '仓库')->select(Warehouse::selectOptions());
            $filter->equal('equipment_id', '设备')->select(Equipment::all()->pluck('name', 'id'));
            $filter->equal('equipment.category', '设备分类')->select(Equipment::CATEGORY);
        });
        return $grid;
    }
}
