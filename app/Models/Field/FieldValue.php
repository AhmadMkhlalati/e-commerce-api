<?php

namespace App\Models\Field;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MainModel;
use App\Models\Field\Field;
use App\Models\Brand\Brand;
use App\Models\Category\Category;
use App\Models\Product\Product;
use Spatie\Translatable\HasTranslations;

class FieldValue extends MainModel


{
    use HasFactory,HasTranslations;
    protected array $translatable=['value'];
    protected $table='fields_values';
    protected $guard_name = 'web';

    public function field(){
        return $this->belongsTo(Field::class,'field_id');
        }

    public function fieldCategorie(){
        return $this->belongsToMany(Field::class,'categories_fields','field_value_id');
    }

    public function category(){
        return $this->belongsToMany(Category::class,'categories_fields','field_value_id','category_id');
    }

    public function brand(){
        return $this->belongsToMany(Brand::class,'brands_fields','field_value_id','brand_id');
    }
    public function fieldBrand(){
        return $this->belongsToMany(Field::class,'brands_fields','field_value_id');
    }
    public function product(){
        return $this->belongsTo(Product::class,'product_id');

    }
}
