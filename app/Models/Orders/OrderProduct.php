<?php

namespace App\Models\Orders;

use App\Models\MainModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class OrderProduct extends MainModel
{

    use HasFactory,HasTranslations;

    protected $table = 'order_products';
    protected $translatable = [];
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'tax_percentage',
        'tax_amount',
        'total'
    ];


}
