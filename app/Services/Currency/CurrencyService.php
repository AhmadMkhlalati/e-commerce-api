<?php

namespace App\Services\Currency;

use App\Http\Resources\CurrencyResource;
use App\Models\Currency\Currency;
use App\Models\Currency\CurrencyHistory;
use PhpParser\Node\Expr\Cast\Double;

class CurrencyService {

    public static function updateCurrencyHistory(Currency $currency,float $newRate) : Void{

        if($currency->rate ?? null != $newRate){

            //create a new history
            CurrencyHistory::create([
              'currency_id'=> $currency->id,
              'rate' => $newRate
            ]);

        }
    }
    }




