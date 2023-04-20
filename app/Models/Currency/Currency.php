<?php

namespace App\Models\Currency;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MainModel;
use App\Models\Currency\CurrencyHistory;
use App\Models\Price\Price;
use Spatie\Translatable\HasTranslations;

class Currency extends MainModel
{
    use HasFactory, HasTranslations;

    protected $translatable = ['name'];
    protected $table = 'currencies';
    public static $imagesPath =  [
        'images' => 'currencies/images',
    ];

    public function currencyHistory()
    {
        return $this->hasMany(CurrencyHistory::class, 'currency_id');
    }
    public function price()
    {
        return $this->hasMany(Price::class, 'currency_id');
    }
}
