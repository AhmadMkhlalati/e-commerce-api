<?php

namespace App\Models\Label;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MainModel;
use App\Models\Category\Category;
use App\Models\Brand\brand;
use App\Models\Product\Product;
use Spatie\Translatable\HasTranslations;

class Label extends MainModel
{
    use HasFactory, HasTranslations;

    protected $translatable = ['title'];
    protected $table = 'labels';
    protected $guard_name = 'web';
    public static $imagesPath = [
        'images' => 'labels/images',
    ];
    public static $entities = 'category,product,brand';

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'categories_labels', 'label_id', 'category_id');
    }
    public function brands()
    {
        return $this->belongsToMany(brand::class, 'brands_labels', 'label_id');
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'product_id');
    }
}
