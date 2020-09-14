<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Equipment\BatchDownloadTag;
use App\Admin\Actions\Equipment\DownloadTag;
use App\Equipment;
use App\EquipmentDetail;
use App\Warehouse;
use Carbon\Carbon;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Grid;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class EquipmentDetailController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '设备台账';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new EquipmentDetail());
        $grid->model()->with('equipment', 'warehouse');
        $grid->column('id', __('Id'));
        $grid->column('warehouse_id', '仓库')->display(function ($val) {
            return $this->warehouse->name;
        });
        $grid->column('equipment_name', '设备名称')->display(function ($val) {
            return $this->equipment->name;
        });
        $grid->column('equipment_code', '设备编码')->display(function ($val) {
            return $this->equipment->code;
        });
        $grid->column('price', '设备价格')->sortable();
        $grid->column('is_in_stock', '是否在库')->display(function ($val) {
            if ($val) {
                return '<span style="color: green">在库</span>';
            }
            return '<span style="color: red">已出库</span>';
        });
        $grid->column('check_date', '下次检验日期')->display(function ($val) {
            return Carbon::parse($val)->format('Y-m-d');
        })->sortable();
        $grid->column('created_at', __('Created at'))->sortable();
        $grid->column('updated_at', __('Updated at'));
        $grid->disableCreateButton();
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableDelete();
            $actions->disableView();
            $actions->add(new DownloadTag());
        });
        $grid->disableColumnSelector();
        $grid->batchActions(function (Grid\Tools\BatchActions $actions) {
            $actions->disableDelete();
            $actions->add(new BatchDownloadTag);
        });
        $grid->filter(function (Grid\Filter $filter) {
            $filter->disableIdFilter();
            $filter->equal('warehouse_id', '仓库')->select(Warehouse::selectOptions());
            $filter->equal('equipment_id', '设备')->select(Equipment::all()->pluck('name', 'id'));
            $filter->equal('equipment.category', '设备分类')->select(Equipment::CATEGORY);
            $filter->equal('is_in_stock', '是否在库')->radio(['1' => '在库', '0' => '已出库']);
            $filter->between('check_date', '检验日期')->date();
        });
        return $grid;
    }

    public function tag(EquipmentDetail $equipment)
    {
        return view('tags.tags', ['equipment' => collect(Arr::wrap($equipment))->chunk(3)]);
    }

    public function tags(Request $request)
    {
        $ids       = $request->get('id');
        $equipment = EquipmentDetail::query()
            ->with('equipment', 'warehouse')
            ->whereIn('id', explode(',', $ids))
            ->get();
        return view('tags.tags', ['equipment' => $equipment->chunk(3)]);
    }
}
