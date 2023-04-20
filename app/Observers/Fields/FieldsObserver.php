<?php

namespace App\Observers\Fields;

use App\Models\Field\Field;
use App\Models\Field\FieldValue;

class FieldsObserver
{
    /**
     * Handle the Field "created" event.
     *
     * @param  \App\Models\Field  $field
     * @return void
     */
    public function created(Field $field)
    {
        //
    }

    /**
     * Handle the Field "updated" event.
     *
     * @param  \App\Models\Field  $field
     * @return void
     */
    public function updated(Field $field)
    {
        //
    }

    /**
     * Handle the Field "deleted" event.
     *
     * @param \App\Models\Field $field
     * @return void
     * @throws \Exception
     */
    public function deleted(Field $field)
    {
        if($field->category->count() != 0){
            throw new \Exception('Can\'t delete field attached to categories.');
        }

        if($field->brand->count() != 0){
            throw new \Exception('Can\'t delete field attached to brands.');
        }

        if($field->product->count() != 0){
            throw new \Exception('Can\'t delete field attached to products.');
        }

        FieldValue::query()->where('field_id',$field->id)->delete();
    }

    /**
     * Handle the Field "restored" event.
     *
     * @param  \App\Models\Field  $field
     * @return void
     */
    public function restored(Field $field)
    {
        //
    }

    /**
     * Handle the Field "force deleted" event.
     *
     * @param  \App\Models\Field  $field
     * @return void
     */
    public function forceDeleted(Field $field)
    {
        //
    }
}
