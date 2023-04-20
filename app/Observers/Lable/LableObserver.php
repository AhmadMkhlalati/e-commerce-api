<?php

namespace App\Observers\Lable;

use App\Models\Label\Label;

class LableObserver
{
    /**
     * Handle the Lable "created" event.
     *
     * @param  \App\Models\Label  $label
     * @return void
     */
    public function created(Label $label)
    {
        //
    }

    /**
     * Handle the Lable "updated" event.
     *
     * @param  \App\Models\Label  $label
     * @return void
     */
    public function updated(Label $label)
    {
        //
    }

    /**
     * Handle the Lable "deleted" event.
     *
     * @param \App\Models\Label $label
     * @return void
     * @throws \Exception
     */
    public function deleted(Label $label)
    {
        if($label->brands->count() != 0){
            throw new \Exception('Can\'t delete label attached to brands.');
        }
        if($label->products->count() != 0){
            throw new \Exception('Can\'t delete label attached to products.');
        }

    }

    /**
     * Handle the Lable "restored" event.
     *
     * @param  \App\Models\Label  $label
     * @return void
     */
    public function restored(Label $label)
    {
        //
    }

    /**
     * Handle the Lable "force deleted" event.
     *
     * @param  \App\Models\Label  $label
     * @return void
     */
    public function forceDeleted(Label $label)
    {
        //
    }
}
