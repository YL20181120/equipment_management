<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMainTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 仓库
        Schema::create('warehouses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('equipments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->index()->unique();
            $table->string('name');
            $table->string('category')->comment('分类');
            $table->string('model')->comment('规格');
            $table->decimal('price', 10, 2)->comment('单价');
            $table->string('unit')->comment('单位');
            $table->string('remark')->comment('备注');
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
        Schema::dropIfExists('warehouses');
        Schema::dropIfExists('equipments');
    }
}
