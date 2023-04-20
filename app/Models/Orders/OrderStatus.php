<?php

namespace App\Models\Orders;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;


class OrderStatus extends Model
{
    use HasFactory,HasTranslations;
    protected array $translatable=['name'];

    public function products(){
        return $this->hasMany(Order::class,'order_id','id');
    }
}
