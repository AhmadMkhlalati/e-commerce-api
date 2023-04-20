<?php

namespace App\Observers\Fields;

use App\Models\Field\FieldValue;

class FieldsValesObserver
{
    /**
     * Handle the FieldsValue "created" event.
     *
     * @param  \App\Models\FieldValue  $fieldsValue
     * @return void
     */
    public function created(FieldValue $fieldsValue)
    {
        //
    }

    /**
     * Handle the FieldsValue "updated" event.
     *
     * @param  \App\Models\FieldValue  $fieldsValue
     * @return void
     */
    public function updated(FieldValue $fieldsValue)
    {
        //
    }

    /**
     * Handle the FieldsValue "deleted" event.
     *
     * @param  \App\Models\FieldValue  $fieldsValue
     * @return void
     */
    public function deleted(FieldValue $fieldsValue)
    {

    }

    /**
     * Handle the FieldsValue "restored" event.
     *
     * @param  \App\Models\FieldValue  $fieldsValue
     * @return void
     */
    public function restored(FieldValue $fieldsValue)
    {
        //
    }

    /**
     * Handle the FieldsValue "force deleted" event.
     *
     * @param  \App\Models\FieldValue  $fieldsValue
     * @return void
     */
    public function forceDeleted(FieldValue $fieldsValue)
    {
        //
    }
}
