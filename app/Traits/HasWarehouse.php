<?php


namespace App\Traits;


use App\Warehouse;

trait HasWarehouse
{
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
