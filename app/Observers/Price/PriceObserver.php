<?php

namespace App\Observers\Price;

use App\Models\Price\Price;

class PriceObserver
{
    /**
     * Handle the Price "created" event.
     *
     * @param  \App\Models\Price  $price
     * @return void
     */
    public function created(Price $price)
    {
        //
    }

    /**
     * Handle the Price "updated" event.
     *
     * @param  \App\Models\Price  $price
     * @return void
     */
    public function updated(Price $price)
    {
        //
    }

    /**
     * Handle the Price "deleted" event.
     *
     * @param \App\Models\Price $price
     * @return void
     * @throws \Exception
     */
    public function deleted(Price $price)
    {
        if($price->originalPricesChildren->count() != 0){
            throw new \Exception('The Price is a parent to other prices');
        }
    }

    /**
     * Handle the Price "restored" event.
     *
     * @param  \App\Models\Price  $price
     * @return void
     */
    public function restored(Price $price)
    {
        //
    }

    /**
     * Handle the Price "force deleted" event.
     *
     * @param  \App\Models\Price  $price
     * @return void
     */
    public function forceDeleted(Price $price)
    {
        //
    }
}
