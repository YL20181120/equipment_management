<?php

namespace App\Admin\Controllers;

use App\Admin\Metrics\Dashboard;
use App\Http\Controllers\Controller;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->title('Dashboard')
//            ->description('Description...')
            ->row(function (Row $row) {
                $row->column(8, function (Column $column) {
                    $column->append(Dashboard::checkEquipment());
                });
            });
    }
}
