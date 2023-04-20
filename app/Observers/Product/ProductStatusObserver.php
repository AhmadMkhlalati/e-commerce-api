<?php

namespace App\Observers\Product;

use App\Models\Product\ProductStatus;

class ProductStatusObserver
{
    /**
     * Handle the ProductStatus "created" event.
     *
     * @param  \App\Models\ProductStatus  $productStatus
     * @return void
     */
    public function created(ProductStatus $productStatus)
    {
        //
    }

    /**
     * Handle the ProductStatus "updated" event.
     *
     * @param  \App\Models\ProductStatus  $productStatus
     * @return void
     */
    public function updated(ProductStatus $productStatus)
    {
        //
    }

    /**
     * Handle the ProductStatus "deleted" event.
     *
     * @param  \App\Models\ProductStatus  $productStatus
     * @return void
     */
    public function deleted(ProductStatus $productStatus)
    {

    }

    /**
     * Handle the ProductStatus "deleted" event.
     *
     * @param \App\Models\ProductStatus $productStatus
     * @return void
     * @throws \Exception
     */
    public function deleting(ProductStatus $productStatus)
    {
        if($productStatus->products->count() != 0){
            throw new \Exception('This status is in use you cant delete it ');
        }
    }
    /**
     * Handle the ProductStatus "restored" event.
     *
     * @param  \App\Models\ProductStatus  $productStatus
     * @return void
     */
    public function restored(ProductStatus $productStatus)
    {
        //
    }

    /**
     * Handle the ProductStatus "force deleted" event.
     *
     * @param  \App\Models\ProductStatus  $productStatus
     * @return void
     */
    public function forceDeleted(ProductStatus $productStatus)
    {
        //
    }
}
