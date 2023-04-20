<?php

namespace App\Observers\Discount;

use App\Models\Discount\Discount;

class DiscountObserver
{
    /**
     * Handle the Discount "created" event.
     *
     * @param  \App\Models\Discount  $discount
     * @return void
     */
    public function created(Discount $discount)
    {
        //
    }

    /**
     * Handle the Discount "updated" event.
     *
     * @param  \App\Models\Discount  $discount
     * @return void
     */
    public function updated(Discount $discount)
    {
        //
    }

    /**
     * Handle the Discount "deleted" event.
     *
     * @param  \App\Models\Discount  $discount
     * @return void
     */
    public function deleted(Discount $discount)
    {
        if($discount->category->count() != 0){
            throw new \Exception('can\'t delete discount used on some categories');
        }
        if($discount->tags->count() != 0){
            throw new \Exception('can\'t delete discount used on some tags');

        }
        if($discount->brand->count() != 0){
            throw new \Exception('can\'t delete discount used on some brands');

        }

    }

    /**
     * Handle the Discount "restored" event.
     *
     * @param  \App\Models\Discount  $discount
     * @return void
     */
    public function restored(Discount $discount)
    {
        //
    }

    /**
     * Handle the Discount "force deleted" event.
     *
     * @param  \App\Models\Discount  $discount
     * @return void
     */
    public function forceDeleted(Discount $discount)
    {
        //
    }
}
