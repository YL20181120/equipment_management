<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\EquipmentIn\DownloadTag;
use App\Equipment;
use App\EquipmentDetail;
use App\Warehouse;
use App\WarehouseEquipmentIn;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

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
        $grid->disableColumnSelector();
        $grid->filter(function (Grid\Filter $filter) {
            $filter->disableIdFilter();
            $filter->equal('warehouse_id', '仓库')->select(Warehouse::selectOptions());
        });
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableEdit();
            $actions->disableDelete();
            $actions->add(new DownloadTag);
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

    /**
     * Create interface.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function back(Content $content)
    {
        if (\request()->isMethod('post')) {
            $data = request()->all();
            DB::transaction(function () use ($data) {
                // 创建入库单
                $warehouse_equipment_in = new WarehouseEquipmentIn([
                    'user_id' => Admin::user()->id,
                    'warehouse_id' => $data['warehouse_id'],
                    'type' => '归还'
                ]);
                $warehouse_equipment_in->save();
                // 创建入库明细
                $items = collect($data['items']);
                $items->map(function ($item) use ($data) {
                    if (!EquipmentDetail::query()
                        ->where('warehouse_id', $data['warehouse_id'])
                        ->where('equipment_id', $item['equipment_id'])
                        ->where('id', $item['equipment_detail_id'])
                        ->where('is_in_stock', 0)->exists()) {
                        throw new \Exception('设备在库或者该设备不属于该仓库');
                    }
                });
                $warehouse_equipment_in_details = $items->groupBy('equipment_id')->map(function ($item, $key) use ($data) {
                    return [
                        'warehouse_id' => $data['warehouse_id'],
                        'user_id' => Admin::user()->id,
                        'equipment_id' => $key,
                        'stock_in' => $item->count(),
                        'check_date' => null,
                        'rest' => ['in_ids' => $item->pluck('equipment_detail_id')->toArray()]
                    ];
                });
                $warehouse_equipment_in->items()->createMany($warehouse_equipment_in_details->values()->toArray());
                // 更新在库设备状态
                $items->map(function ($item) use ($data) {
                    EquipmentDetail::query()
                        ->where('warehouse_id', $data['warehouse_id'])
                        ->where('equipment_id', $item['equipment_id'])
                        ->where('id', $item['equipment_detail_id'])
                        ->update(['is_in_stock' => 1]);
                });
            });
            admin_toastr(trans('admin.save_succeeded'));
            return redirect(admin_url('warehouse-equipment-ins'));
        }
        return $content
            ->title($this->title())
            ->description($this->description['create'] ?? trans('admin.create'))
            ->body($this->backForm());
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function backForm()
    {
        $form = new Form(new WarehouseEquipmentIn());
        $form->setAction(admin_url('warehouse-equipment-ins/back'));
        $form->select('type', '入库类型')
            ->options(WarehouseEquipmentIn::TYPE_OPTIONS)
            ->required()
            ->disable()
            ->value('归还');
        $form->select('warehouse_id', '仓库')->options(Warehouse::selectOptions())->required();
        $form->divider();
        $form->table('items', '入库明细', function (Form\NestedForm $form) {
            $form->select('equipment_id', '设备类型')->options(Equipment::all()->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'label' => sprintf("%s-%s-%s", $item->name, $item->code, $item->model)
                ];
            })
                ->pluck('label', 'id'))
                ->load('equipment_detail_id', admin_url('api/equipment/details') . '?is_in_stock=0');
            $form->select('equipment_detail_id', '设备');
        })->required();
        return $form;
    }

    public function tags(WarehouseEquipmentIn $in)
    {
        $in->loadMissing('equipment.warehouse', 'equipment.equipment');
        $equipment = $in->equipment;
        return view('tags.tags', ['equipment' => $equipment->chunk(3)]);
    }
}
