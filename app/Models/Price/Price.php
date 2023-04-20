<?php

namespace App\Models\Price;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MainModel;
use App\Models\Product\Product;
use App\Models\Currency\Currency;

class Price extends MainModel
{
    use HasFactory;
    protected $table='prices';
    protected $guard_name = 'web';
    protected array $translatable=['name'];


    public function currency(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Currency::class,'currency_id');

    }
    public function products(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Product::class,'products_prices' ,'price_id','product_id');
    }

    public function originalPrice(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(self::class,'original_price_id','id');
    }

    public function originalPricesChildren(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(self::class, 'original_price_id','id');
    }

}
