<?php

namespace App\Admin\Controllers;

use App\Equipment;
use App\Warehouse;
use App\WarehouseEquipmentIn;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class WarehouseEquipmentInController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '入库单';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new WarehouseEquipmentIn());
        $grid->model()->with('user', 'warehouse')->latest();
        $grid->column('id', __('Id'))->display(function ($val) {
            return "<a href='" . route('admin.warehouse-equipment-ins.show', $val) . "'>{$val}</a>";
        });
        $grid->column('user_id', '入库操作员')->display(function ($val) {
            return $this->user != null ? sprintf('[%s]%s', $this->user_id, $this->user->name) : '-';
        });
        $grid->column('warehouse_id', '仓库')->display(function ($val) {
            return $this->warehouse->name;
        });
        $grid->column('type', '入库类型');
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
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
        $show = new Show(WarehouseEquipmentIn::findOrFail($id)->loadMissing('items.equipment'));

        $show->field('id', __('Id'));
        $show->field('user.name', '操作员');
        $show->field('warehouse.name', '仓库');
        $show->field('type', __('Type'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->panel()
            ->tools(function ($tools) {
                $tools->disableEdit();
                $tools->disableDelete();
            });;
        $show->items('入库单详情', function (Grid $items) {
            $items->id();
            $items->column('equipment.name', '设备名称');
            $items->column('equipment.code', '设备编码');
            $items->column('equipment.model', '设备规格');
            $items->stock_in('入库数量');
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
        $form = new Form(new WarehouseEquipmentIn());
        $form->hidden('user_id')->value(Admin::user()->id);
        $form->select('type', '入库类型')->options(WarehouseEquipmentIn::TYPE_OPTIONS)->required();
        $form->select('warehouse_id', '仓库')->options(Warehouse::selectOptions())->required();
        $form->divider();
        $form->table('items', '入库明细', function (Form\NestedForm $form) {
            $form->select('equipment_id', '设备')->options(Equipment::all()->transform(function ($item) {

                return [
                    'id' => $item->id,
                    'label' => sprintf("%s-%s-%s", $item->name, $item->code, $item->model)
                ];
            })->pluck('label', 'id'));
            $form->text('stock_in', '入库数量');
            $form->date('check_date', '下次检验日期');
        })->required();
        return $form;
    }
}
