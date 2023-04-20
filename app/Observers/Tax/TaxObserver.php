<?php

namespace App\Observers\Tax;

use App\Models\Product\Product;
use App\Models\Tax\Tax;
use App\Models\Tax\TaxComponent;

class TaxObserver
{
    /**
     * Handle the Tax "created" event.
     *
     * @param  \App\Models\Tax  $tax
     * @return void
     */
    public function created(Tax $tax)
    {
        //
    }

    /**
     * Handle the Tax "updated" event.
     *
     * @param  \App\Models\Tax  $tax
     * @return void
     */
    public function updated(Tax $tax)
    {
        //
    }

    /**
     * Handle the Tax "deleted" event.
     *
     * @param \App\Models\Tax $tax
     * @return void
     * @throws \Exception
     */
    public function deleted(Tax $tax)
    {
        if($tax->product->count() != 0){
            throw new \Exception('Sorry but this tax is already in use can\'t delete it');
        }
        if($tax->taxComponentsParents->count != 0){
            throw new \Exception('Sorry but this tax is already in use can\'t delete it');

        }

        Product::query()->where('tax_id',$tax->id)->update(['tax_id' => null]);
        TaxComponent::query()->where('tax_id',$tax->id)->delete();
    }

    /**
     * Handle the Tax "restored" event.
     *
     * @param  \App\Models\Tax  $tax
     * @return void
     */
    public function restored(Tax $tax)
    {
        //
    }

    /**
     * Handle the Tax "force deleted" event.
     *
     * @param  \App\Models\Tax  $tax
     * @return void
     */
    public function forceDeleted(Tax $tax)
    {
        //
    }
}
