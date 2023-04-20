<?php

namespace App\Models\Tag;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MainModel;
use App\Models\Category\Category;
use App\Models\Discount\Discount;
use App\Models\Brand\Brand;
use App\Models\Product\Product;
use Spatie\Translatable\HasTranslations;

class Tag extends MainModel
{
    use HasFactory,HasTranslations;
    protected array $translatable =['name'];
    protected $table='tags';
    protected $guard_name = 'web';

    public function category(){
        return $this->belongsToMany(Category::class,'discounts_entities','tag_id','category_id');
    }

    public function discount(){
        return $this->belongsToMany(Discount::class,'discounts_entities','tag_id','discount_id');
    }

    public function brand(){
        return $this->belongsToMany(Brand::class,'discounts_entities','tag_id','brand_id');
    }
    public function products(){
        return $this->belongsToMany(Product::class,'products_tags','product_id','tag_id');

    }
}
