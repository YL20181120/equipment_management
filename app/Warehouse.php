<?php

namespace App;

use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use SoftDeletes;
    use ModelTree;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTitleColumn('name');
    }
}
