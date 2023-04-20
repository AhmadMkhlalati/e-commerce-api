<?php

namespace App\Models\Attribute;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MainModel;
use App\Models\Attribute\AttributeValue;
use App\Models\Product\Product;


class Attribute extends MainModel
{
    use HasFactory;

    protected $table='attributes';
    protected $guard_name = 'web';

    public $translatable = ['title'];

    public function attributeValues(){
        return $this->hasMany(AttributeValue::class,'attribute_id','id');
    }

    public function product(){
        return $this->belongsTo(Product::class,'product_id');

    }


}
