<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehouseEquipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('equipments', function (Blueprint $table) {
            $table->string('manufacturer')->comment('生产厂家');
        });
        // 仓库库存
        Schema::create('warehouse_equipments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('equipment_id')->index();
            $table->unsignedBigInteger('warehouse_id')->index();
            $table->unsignedBigInteger('stock')->default(0)->comment('库存');
            $table->timestamps();
        });
        // 设备明细, 每个设备单独一条
        Schema::create('equipment_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('equipment_id')->index();
            $table->unsignedBigInteger('warehouse_id')->index();
            $table->decimal('price', 10, 2)->comment('单价');
            $table->timestamp('check_date')->comment('检测到期日期');
            $table->timestamps();
        });
        // 仓库入库单
        Schema::create('warehouse_equipment_ins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('warehouse_id')->index();
            $table->string('type')->index()->comment('入库类型');
            $table->timestamps();
            $table->softDeletes();
        });
        // 仓库入库单明细
        Schema::create('warehouse_equipment_in_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('warehouse_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('warehouse_equipment_in_id')->index();
            $table->unsignedBigInteger('equipment_id')->index();
            $table->unsignedBigInteger('stock_in')->default(0)->comment('入库数量');
            $table->timestamp('check_date')->comment('检测到期日期');
            $table->timestamps();
            $table->softDeletes();
        });
        // 仓库出库单
        Schema::create('warehouse_equipment_outs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('warehouse_id')->index();
            $table->string('use_name')->nullable()->comment('使用单位');
            $table->timestamps();
            $table->softDeletes();
        });
        // 仓库出库单明细
        Schema::create('warehouse_equipment_out_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('warehouse_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('warehouse_equipment_out_id')->index();
            $table->unsignedBigInteger('equipment_id')->index();
            $table->unsignedBigInteger('stock_out')->default(0)->comment('出库数量');
            $table->json('rest');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('equipments', function (Blueprint $table) {
            $table->dropColumn('manufacturer');
        });
        Schema::dropIfExists('warehouse_equipments');
        Schema::dropIfExists('equipment_details');
        Schema::dropIfExists('warehouse_equipment_ins');
        Schema::dropIfExists('warehouse_equipment_in_details');
        Schema::dropIfExists('warehouse_equipment_outs');
        Schema::dropIfExists('warehouse_equipment_out_details');
    }
}
