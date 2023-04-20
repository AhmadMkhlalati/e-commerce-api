<?php

namespace App\Observers\Order;

use App\Models\Orders\OrderStatus;

class OrderStatusObserver
{
    /**
     * Handle the OrderStatus "created" event.
     *
     * @param  \App\Models\OrderStatus  $orderStatus
     * @return void
     */
    public function created(OrderStatus $orderStatus)
    {
        //
    }

    /**
     * Handle the OrderStatus "updated" event.
     *
     * @param  \App\Models\OrderStatus  $orderStatus
     * @return void
     */
    public function updated(OrderStatus $orderStatus)
    {
        //
    }

    /**
     * Handle the OrderStatus "deleted" event.
     *
     * @param  \App\Models\OrderStatus  $orderStatus
     * @return void
     */
    public function deleted(OrderStatus $orderStatus)
    {
        if($orderStatus->products->count() != 0){
            throw new \Exception('Can\'t delete the status used in orders.');
        }
    }

    /**
     * Handle the OrderStatus "restored" event.
     *
     * @param  \App\Models\OrderStatus  $orderStatus
     * @return void
     */
    public function restored(OrderStatus $orderStatus)
    {
        //
    }

    /**
     * Handle the OrderStatus "force deleted" event.
     *
     * @param  \App\Models\OrderStatus  $orderStatus
     * @return void
     */
    public function forceDeleted(OrderStatus $orderStatus)
    {
        //
    }
}
