<?php


namespace App\Traits;


use App\Equipment;

trait HasEquipment
{
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
