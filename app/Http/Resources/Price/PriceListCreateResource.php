<?php

namespace App\Http\Resources\Price;

use App\Models\Price\Price;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class PriceListCreateResource extends JsonResource
{
    private static $data;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $prices = self::$data[0];
        $productPrices = self::$data[2];

        $pricesResult = [];
        $pricesResult['code'] = $this->code;
        $pricesResult['item'] = $this->name;
        $pricesResult['UOM'] = $this->whenLoaded('unit') ?  $this->whenLoaded('unit')->code : 'NON';

        foreach ($prices as $price){
            $productPrice = collect($productPrices)->where('product_id',$this->id)->where('price_id',$price->id)->first();
            if(is_null($productPrice)){
                $pricesResult['price_'.$price->id]['id'] = null;
                $pricesResult['price_'.$price->id]['price'] = 0;
                $pricesResult['price_'.$price->id]['price_id'] = $price->id;
                $pricesResult['price_'.$price->id]['is_virtual'] = (bool)$price->is_virtual;

                if(!$price->is_virtual){
                    continue;
                }

                $originalPrice = $productPrices->where('price_id',$price->original_price_id)->where('product_id',$this->id)->first();
                if(is_null($originalPrice)){
                    continue;
                }
                $pricesResult['price_'.$price->id]['price'] = $originalPrice->price * ($price->percentage/100);

                continue;
            }

            $pricesResult['price_'.$price->id]['id'] = $productPrice->id;
            $pricesResult['price_'.$price->id]['price'] = $productPrice->price;
            $pricesResult['price_'.$price->id]['price_id'] = $price->id;
            $pricesResult['price_'.$price->id]['is_virtual'] = (bool)$price->is_virtual;
        }

        return ($pricesResult);

    }


    public static function customCollection($products,...$data): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        //you can add as many params as you want.
        self::$data = $data;
        return parent::collection($products);
    }
}
