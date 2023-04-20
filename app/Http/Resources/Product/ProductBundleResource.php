<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Price\DefaultBundlePrice;
use App\Http\Resources\Price\PriceBundleResource;
use App\Models\Settings\Setting;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;

class ProductBundleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $defaultPriceId = Cache::get(Setting::$cacheKey)->where('title', 'default_pricing_class')->pluck('value', 'title')->toArray()['default_pricing_class'];
        $defaultPrice = DefaultBundlePrice::collection($this->price()->where('price_id', $defaultPriceId)->get());

        return [
            'id' => $this->id,
            'name' => $this->getTranslations('name'),
            'image' => $this->image && !empty($this->image) ?  getAssetsLink('storage/' . $this->image) : 'default_image',
            'prices' =>  arrayToObject(PriceBundleResource::collection($this->whenLoaded('price'))),
            'default_price' => $defaultPrice
        ];
    }
}
