<?php

namespace App\Models\Field;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MainModel;
use App\Models\Field\FieldValue;
use App\Models\Category\Category;
use App\Models\Brand\Brand;
use App\Models\Product\Product;
use Spatie\Translatable\HasTranslations;

class Field extends MainModel
{
    use HasFactory, HasTranslations;

    protected array $translatable = ['title'];
    protected $table = 'fields';
    // array since I have used it product service and I need it to be array
    public static $fieldTypes = ['checkbox', 'text', 'select', 'textarea', 'date'];
    public static $entities = 'category,product,brand';

    public function fieldValue()
    {
        return $this->hasMany(fieldValue::class, 'field_id');
    }
    public function category()
    {
        return $this->belongsToMany(Category::class, 'categories_fields', 'field_id', 'category_id');
    }
    public function fieldValueCategoire()
    {
        return $this->belongsToMany(FieldValue::class, 'categories_fields', 'field_id');
    }


    public function brand()
    {
        return $this->belongsToMany(Brand::class, 'brands_fields', 'field_id', 'brand_id');
    }
    public function fieldValueBrand()
    {
        return $this->belongsToMany(FieldValue::class, 'brands_fields', 'field_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
