<?php

namespace App\Observers\Category;


use App\Models\Category\Category;
use App\Models\Discount\DiscountEntity;
use App\Models\Product\Product;

class CategoryObserver
{

    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\Category  $category
     * @return void
     */
    public function created(Category $category)
    {
        dd('from create user observer');
    }

    /**
     * Handle the User "updated" event.
     *
     * @return void
     */
    public function updated(Category $category)
    {
    }

    /**
     * Handle the User "deleted" event.
     *
     * @return void
     */
    public function deleted(Category $category)
    {
        if($category->children->count() != 0){
            throw new \Exception('can\'t delete this category, it is a parent to other categories');
        }
        if($category->label->count() != 0){
            throw new \Exception('can\'t delete this category, it\'s used in labels');
        }
        if($category->products->count() != 0){
            throw new \Exception('can\'t delete this category, it\'s used in labels');
        }
        if($category->multipleProducts->count() != 0){
            throw new \Exception('can\'t delete this category, it\'s used in labels');
        }
        //set related products to null
        Product::query()->where('category_id',$category->id)->update(['category_id' => null]);

        DiscountEntity::query()->where('category_id',$category->id)->update(['category_id' => null]);

    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(Category $category)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(Category $category)
    {
        //
    }

}
