<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix' => config('admin.route.prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
    'as' => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('warehouses', WarehouseController::class);
    $router->resource('equipment', EquipmentController::class);
    $router->resource('warehouse-equipments', WarehouseEquipmentController::class);
    $router->resource('equipment-details', EquipmentDetailController::class);
    $router->resource('warehouse-equipment-ins', WarehouseEquipmentInController::class);
    $router->resource('warehouse-equipment-in-details', WarehouseEquipmentInDetailController::class);
    $router->resource('warehouse-equipment-outs', WarehouseEquipmentOutController::class);
    $router->resource('warehouse-equipment-out-details', WarehouseEquipmentOutDetailController::class);
});
