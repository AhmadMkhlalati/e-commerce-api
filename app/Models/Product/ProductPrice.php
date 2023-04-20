<?php

namespace App\Models\Product;

use App\Models\Price\Price;
use Carbon\Carbon;
use App\Models\Currency\Currency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;
class ProductPrice extends Model
{
    use HasFactory;
    protected $table = 'products_prices';

    public function products(){
        return $this->hasMany(Product::class, 'id','product_id');
    }

    public function prices(){
        return $this->hasOne(Price::class,'id','price_id');
    }



//
//    public function priceList(){
//        return $this->price->priceList;
//    }

}
