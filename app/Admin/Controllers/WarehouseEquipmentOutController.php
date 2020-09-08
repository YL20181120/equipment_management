<?php

namespace App\Admin\Controllers;

use App\Equipment;
use App\Warehouse;
use App\WarehouseEquipmentOut;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class WarehouseEquipmentOutController extends AdminController
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
        $grid = new Grid(new WarehouseEquipmentOut());

        $grid->model()->with('user', 'warehouse')->latest();
        $grid->column('id', __('Id'))->display(function ($val) {
            return "<a href='" . route('admin.warehouse-equipment-outs.show', $val) . "'>{$val}</a>";
        });
        $grid->column('user_id', '出库操作员')->display(function ($val) {
            return $this->user != null ? sprintf('[%s]%s', $this->user_id, $this->user->name) : '-';
        });
        $grid->column('warehouse_id', '仓库')->display(function ($val) {
            return $this->warehouse->name;
        });
        $grid->column('use_name', '使用单位');
        $grid->column('created_at', __('Created at'));
        $grid->disableActions();
        $grid->disableColumnSelector();
        $grid->filter(function (Grid\Filter $filter) {
            $filter->disableIdFilter();
            $filter->equal('warehouse_id', '仓库')->select(Warehouse::selectOptions());
        });
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(WarehouseEquipmentOut::findOrFail($id)->loadMissing('items.equipment'));

        $show->field('id', __('Id'));
        $show->field('user.name', '操作员');
        $show->field('warehouse.name', '仓库');
        $show->field('use_name', '使用单位');
        $show->field('created_at', '出库时间');
        $show->panel()
            ->tools(function ($tools) {
                $tools->disableEdit();
                $tools->disableDelete();
            });;
        $show->items('出库单详情', function (Grid $items) {
            $items->id();
            $items->column('equipment.name', '设备名称');
            $items->column('equipment.code', '设备编码');
            $items->column('equipment.model', '设备规格');
            $items->stock_out('出库数量');
            $items->disableActions();
            $items->disableColumnSelector();
            $items->disableBatchActions();
            $items->disableCreateButton();
            $items->disableExport();
            $items->disablePagination();
            $items->disableFilter();
        });
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new WarehouseEquipmentOut());

        $form->hidden('user_id')->value(Admin::user()->id);
        $form->select('warehouse_id', '仓库')->options(Warehouse::selectOptions())
            ->required()
            ->load('equipment_id', admin_url('api/warehouse/details'));
        $form->text('use_name', '使用单位')->required();
        $form->divider();

        $form->table('items', '出库明细', function (Form\NestedForm $form) {
            $form->select('equipment_id', '设备')->options(Equipment::all()->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'label' => sprintf("%s-%s-%s", $item->name, $item->code, $item->model)
                ];
            })->pluck('label', 'id'));
//                ->load('equipment_detail_id', admin_url('api/equipment/details'));
            $form->select('equipment_detail_id', '设备');
            $form->hidden('stock_out', '出库数量')->value(1);
        })->required();
        return $form;
    }
}
