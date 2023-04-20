<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStatus extends Model
{
    use HasFactory;
    protected $table='products_statuses';

    public function products(){
        return $this->hasMany(Product::class,'products_statuses_id','id');
    }
}
