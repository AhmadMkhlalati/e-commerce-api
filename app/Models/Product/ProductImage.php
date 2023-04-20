<?php

namespace App\Models\Product;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MainModel;
use App\Models\Product\Product;
use Spatie\Translatable\HasTranslations;

class ProductImage extends MainModel
{
    use HasFactory,HasTranslations;
    protected $table='products_images';
    protected  $translatable = ['title'];
    protected $fillable = [
        'product_id',
        'image',
        'title',
        'sort',
        'created_at' ,
        'updated_at',
    ];

    public function productImages(){
        return $this->belongsTo(Product::class,'product_id');
    }
}
