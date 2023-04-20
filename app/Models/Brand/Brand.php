<?php

namespace App\Models\Brand;

use App\Models\Category\Category;
use App\Models\Product\Product;
use App\Models\Tax\Tax;
use App\Models\Unit\Unit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MainModel;
use App\Models\Label\Label;
use App\Models\Field\Field;
use App\Models\Field\FieldValue;
use Spatie\Translatable\HasTranslations;

class Brand extends MainModel
{
    use HasFactory, HasTranslations;
    protected array $translatable = ['name', 'meta_title', 'meta_description', 'meta_keyword', 'description'];
    protected $table = 'brands';
    protected $guard_name = 'web';
    public static $imagesPath = [
        'images' => 'brands/images',
    ];

    public function label()
    {
        return $this->belongsToMany(Label::class, 'brands_labels', 'brand_id');
    }
    public function field()
    {
        return $this->belongsToMany(field::class, 'brands_fields', 'brand_id', 'field_id');
    }
    public function fieldValue(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BrandField::class, 'brand_id', 'id');
    }
    public function cateogry(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Category::class, 'category_id');
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id', 'id');
    }
    public function tax()
    {
        return $this->hasMany(Tax::class, 'tax_id');
    }
    public function unit()
    {
        return $this->hasMany(Unit::class, 'unit_id');
    }
}
