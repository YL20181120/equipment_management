<?php

namespace App\Admin\Controllers;

use App\Equipment;
use App\Warehouse;
use App\WarehouseEquipment;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Grid;

class WarehouseEquipmentController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '库存管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new WarehouseEquipment());

        $grid->model()->with('equipment', 'warehouse')->latest();
//        $grid->column('id', __('Id'));
        $grid->column('warehouse_id', '仓库')->display(function ($val) {
            return $this->warehouse->name;
        });
        $grid->column('equipment_name', '设备名称')->display(function ($val) {
            return $this->equipment->name;
        });
        $grid->column('equipment_code', '设备编码')->display(function ($val) {
            return $this->equipment->code;
        });
        $grid->column('equipment_category', '设备分类')->display(function ($val) {
            return Equipment::CATEGORY[$this->equipment->category] ?? '-';
        });
        $grid->column('stock', '库存');
        $grid->disableCreateButton();
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableAll();
        });
        $grid->disableColumnSelector();

        $grid->filter(function (Grid\Filter $filter) {
            $filter->disableIdFilter();
            $filter->equal('warehouse_id', '仓库')->select(Warehouse::selectOptions());
            $filter->equal('equipment_id', '设备')->select(Equipment::all()->pluck('name', 'id'));
            $filter->equal('equipment.category', '设备分类')->select(Equipment::CATEGORY);
        });
//        $grid->column('created_at', __('Created at'));
//        $grid->column('updated_at', __('Updated at'));

        return $grid;
    }
}
