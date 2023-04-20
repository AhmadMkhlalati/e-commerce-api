<?php

namespace App\Http\Resources\Product;

use App\Models\Price\Price;
use App\Models\Settings\Setting;
use Hamcrest\Core\Set;
use Illuminate\Http\Resources\Json\JsonResource;

class SelectProductOrderResource extends JsonResource
{
    private static $data = [];
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $isAllowNegativeQuantity  = Setting::query()->where('title','allow_negative_quantity')->first()->value;
//        $defaultPricingClass = Price::query()->where('is_default')->first()->id;

        $rate = self::$data['currency_rate'];
        $currency = self::$data['currency'];
        if($currency->is_default){
            $rate = 1;
        }
        $priceObject = ($this->whenLoaded('pricesList')->where('price_id',1)->first());
        $price = $priceObject ? $priceObject->price : 0;
        //@TODO:change to code instead of queries just pass an array of the elements, transform them to a collection and simply use the where function
        $currencySymbol = $currency->symbol ?? 'NON';
        $tax = ($this->whenLoaded('tax')->percentage * $price)/100;
        $taxObject = $this->whenLoaded('tax');
        if($taxObject->is_complex){
            $tax = $taxObject->getComplexPrice($price,self::$data['taxComponents']->toArray(),self::$data['tax']->toArray());
        }

        $quantity = $this->quantity;
        $preOrder = false;

        if(!$isAllowNegativeQuantity){
            if(($this->pre_order)){
                $preOrder = true;
                $quantity = '∞';
            }

            if($quantity < 0){
                $quantity = 0;
            }

        }else{
            $preOrder = true;
            $quantity = '∞';

        }

        if($this->type == 'service'){
            $preOrder = true;
        }



        return [
            'id' => (int)$this->id,
            'image'=> $this->image && !empty($this->image) ?  getAssetsLink('storage/'.$this->image) : 'default_image' ,
            'name' => $this->name,
            'quantity' => (int)1,
            'tax' => (float)number_format((float)$tax * $rate,2,'.',''),
            'sku' => $this->sku,
            'original_tax' => (float)number_format((float)$tax,2,'.',''),
            'original_unit_price' => (float)number_format((float)($price + $tax),2,'.',''),
            'unit_price' => (float)number_format((float)($price + $tax) * $rate,2,'.',''),
            'currency_symbol' => $currencySymbol,
            'quantity_in_stock' => !is_numeric($quantity) ? $quantity :  (float)number_format((float)$quantity, 2, '.', ''),
            'edit_status' => false,
            'type' => $this->type,
            'pre_order' => (bool)$preOrder
//            'quantity_in_stock_available' => $this->quantity - $this->minimum_quantity < 0 ? 0 : $this->quantity - $this->minimum_quantity,

        ];
    }


    public static function customCollection($resource, $data): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        //you can add as many params as you want.
        self::$data = $data;
        return parent::collection($resource);
    }
}
