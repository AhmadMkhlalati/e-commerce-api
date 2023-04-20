<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ProductRelated extends Model
{
    use HasFactory, HasTranslations;
    protected $table = 'products_related';
    protected $translatable = ['name'];
    protected $guarded = [];
    public static $childNameStatuses = ['hide', 'default', 'custom'];
    public static $bundle_price_status = ['default', 'from_products'];
}
