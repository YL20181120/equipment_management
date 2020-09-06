<?php

namespace App\Admin\Controllers;

use App\Equipment;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class EquipmentController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '设备管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Equipment());

        $grid->column('id', 'ID');
        $grid->column('code', '设备编码');
        $grid->column('name', '设备名称');
        $grid->column('category', '分类');
        $grid->column('model', '规格型号');
        $grid->column('price', '设备单价');
        $grid->column('unit', '计量单位');
        $grid->column('remark', '备注');
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(Equipment::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('code', '设备编码');
        $show->field('name', '设备名称');
        $show->field('category', '分类');
        $show->field('model', '规格型号');
        $show->field('price', '设备单价');
        $show->field('unit', '计量单位');
        $show->field('remark', '备注');
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Equipment());

        $form->text('code', '设备编码')->rules(function ($form) {
            // 如果不是编辑状态，则添加字段唯一验证
            if (!$id = $form->model()->id) {
                return 'unique:equipments,code';
            }
        });
        $form->text('name', '设备名称');
        $form->select('category', '分类')->options(Equipment::CATEGORY);
        $form->text('model', '规格型号');
        $form->currency('price', '设备单价');
        $form->text('unit', '计量单位')->help('台/件/把...');
        $form->textarea('remark', '备注');

        return $form;
    }
}
