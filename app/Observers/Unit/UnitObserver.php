<?php

namespace App\Observers\Unit;

use App\Models\Product\Product;
use App\Models\Unit\Unit;

class UnitObserver
{
    /**
     * Handle the Unit "created" event.
     *
     * @param  \App\Models\Unit  $unit
     * @return void
     */
    public function created(Unit $unit)
    {
        //
    }

    /**
     * Handle the Unit "updated" event.
     *
     * @param  \App\Models\Unit  $unit
     * @return void
     */
    public function updated(Unit $unit)
    {
        //
    }

    /**
     * Handle the Unit "deleted" event.
     *
     * @param \App\Models\Unit $unit
     * @return void
     * @throws \Exception
     */
    public function deleted(Unit $unit)
    {
//        if(Unit::find($unit->id)->products->count() == 0){
//            throw new \Exception('The unit is attached to products ');
//        }

        Product::query()->where('unit_id',$unit->id)->update(['unit_id' => $unit->id]);
    }

    /**
     * Handle the Unit "restored" event.
     *
     * @param  \App\Models\Unit  $unit
     * @return void
     */
    public function restored(Unit $unit)
    {
        //
    }

    /**
     * Handle the Unit "force deleted" event.
     *
     * @param  \App\Models\Unit  $unit
     * @return void
     */
    public function forceDeleted(Unit $unit)
    {
        //
    }
}
