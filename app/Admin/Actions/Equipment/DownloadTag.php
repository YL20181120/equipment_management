<?php

namespace App\Admin\Actions\Equipment;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class DownloadTag extends RowAction
{
    public $name = '打印标签';

    public function handle(Model $model)
    {
        return $this->response()->download(admin_url('equipment/tag/' . $model->getKey()));
    }
}
