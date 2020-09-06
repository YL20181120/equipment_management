<?php

namespace App\Admin\Controllers;

use App\Warehouse;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Show;
use Encore\Admin\Tree;
use Encore\Admin\Widgets\Box;

class WarehouseController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '仓库管理';

    /**
     * Make a grid builder.
     *
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->title('仓库管理')
//            ->description(trans('admin.list'))
            ->row(function (Row $row) {
                $row->column(6, $this->treeView()->render());

                $row->column(6, function (Column $column) {
                    $form = new \Encore\Admin\Widgets\Form();
                    $form->action(admin_url('warehouses'));

                    $form->select('parent_id', trans('admin.parent_id'))->options(Warehouse::selectOptions());
                    $form->text('name', '仓库名称')->rules('required');
                    $form->hidden('_token')->default(csrf_token());
                    $column->append((new Box(trans('admin.new'), $form))->style('success'));
                });
            });
    }

    /**
     * @return \Encore\Admin\Tree
     */
    protected function treeView()
    {

        $tree = new Tree(new Warehouse());

        $tree->disableCreate();

        $tree->branch(function ($branch) {
            $payload = "<i class='fa fa-building-o'></i>&nbsp;<strong>{$branch['name']}</strong>";
            return $payload;
        });

        return $tree;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected
    function detail($id)
    {
        $show = new Show(Warehouse::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('parent_id', __('Parent id'));
        $show->field('order', __('Order'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('deleted_at', __('Deleted at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected
    function form()
    {
        $form = new Form(new Warehouse());

        $form->text('name', __('Name'));
        $form->number('parent_id', __('Parent id'));
        $form->number('order', __('Order'));

        return $form;
    }
}
