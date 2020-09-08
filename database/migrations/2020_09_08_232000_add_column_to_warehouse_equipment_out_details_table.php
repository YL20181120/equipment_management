<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToWarehouseEquipmentOutDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_equipment_out_details', function (Blueprint $table) {
            $table->unsignedBigInteger('equipment_detail_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouse_equipment_out_details', function (Blueprint $table) {
            $table->dropColumn('equipment_detail_id');
        });
    }
}
