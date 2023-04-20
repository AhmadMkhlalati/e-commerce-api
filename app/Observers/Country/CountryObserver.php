<?php

namespace App\Observers\Country;

use App\Models\Country\Country;

class CountryObserver
{
    /**
     * Handle the Country "created" event.
     *
     * @param  \App\Models\Country  $country
     * @return void
     */
    public function created(Country $country)
    {
        //
    }

    /**
     * Handle the Country "updated" event.
     *
     * @param  \App\Models\Country  $country
     * @return void
     */
    public function updated(Country $country)
    {
        //
    }

    /**
     * Handle the Country "deleted" event.
     *
     * @param \App\Models\Country $country
     * @return void
     * @throws \Exception
     */
    public function deleted(Country $country)
    {

        if($country->products->count() != 0){
            throw new \Exception('Can\'t delete the status used in orders.');
        }
        if($country->shippingOrder->count() != 0){
            throw new \Exception('Can\'t delete the status used for shipping addresses.');
        }
        if($country->billingOrder->count() != 0){
            throw new \Exception('Can\'t delete the status used billing addresses.');
        }

    }

    /**
     * Handle the Country "restored" event.
     *
     * @param  \App\Models\Country  $country
     * @return void
     */
    public function restored(Country $country)
    {
        //
    }

    /**
     * Handle the Country "force deleted" event.
     *
     * @param  \App\Models\Country  $country
     * @return void
     */
    public function forceDeleted(Country $country)
    {
        //
    }
}
