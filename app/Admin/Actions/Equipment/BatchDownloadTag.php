<?php

namespace App\Admin\Actions\Equipment;

use Encore\Admin\Actions\BatchAction;
use Illuminate\Database\Eloquent\Collection;

class BatchDownloadTag extends BatchAction
{
    public $name = '批量打印标签';

    public function handle(Collection $collection)
    {
        return $this->response()->download(admin_url('equipment/tags') . '?id=' . $collection->pluck('id')->join(','));
    }
}
