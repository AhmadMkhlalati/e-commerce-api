<?php

namespace App\Models\Category;

use App\Models\Brand\BrandField;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MainModel;
use App\Models\Label\Label;
use App\Models\Field\Field;
use App\Models\Field\FieldValue;
use App\Models\Tag\Tag;
use App\Models\Discount\Discount;
use App\Models\Brand\Brand;
use App\Models\Product\Product;
use phpDocumentor\Reflection\Types\Boolean;
use Spatie\Translatable\HasTranslations;

class Category extends MainModel
{
    use HasFactory, HasTranslations;
    protected array $translatable = ['name', 'meta_title', 'meta_description', 'meta_keyword', 'description'];
    public static array $filePath =  [
        'images' => 'categories/images',
        'icons' => 'categories/icons',
    ];
    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }
    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function label(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Label::class, 'categories_labels', 'category_id');
    }

    public function fields(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(field::class, 'categories_fields', 'category_id', 'field_id');
    }
    public function fieldValue(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CategoriesFields::class, 'category_id', 'id');
    }
    public function tags(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'discounts_entities', 'category_id', 'tag_id');
    }

    public function discount(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Discount::class, 'discounts_entities', 'category_id', 'discount_id');
    }

    public function brand(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Brand::class, 'discounts_entities', 'category_id', 'brand_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_categories', 'category_id', 'product_id');
    }

    public function multipleProducts()
    {
        return $this->belongsToMany(Product::class, 'products_categories', 'category_id', 'product_id');
    }

    public static function getMaxSortValue($parent_id = null)
    {
        if ($parent_id)
            return self::where('parent_id', $parent_id)->max('sort')  + 1; // get the max sort for child with specific parent

        return self::whereNull('parent_id')->max('sort') + 1; // get the max sort between parents

    }
    public function canDelete(&$message): bool
    {
        if ($this->children()->exists()) {
            $message = 'Sorry but this category is a parent for sub categories!';
            return false;
        }

        if ($this->products()->exists()) {
            $message = 'Sorry but this category is used by products!';
            return false;
        }

        return true;
    }
    public function scopeRootParent($query)
    {
        $query->whereNull('parent_id');
    }
}
