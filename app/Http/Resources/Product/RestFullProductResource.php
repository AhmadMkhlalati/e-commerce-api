<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class RestFullProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->getTranslations('name'),
            'slug' => $this->slug,
            'main_category' => $this->whenLoaded('defaultCategory') ?? [],
            'code' => $this->code,
            'sku' => $this->sku,
            'type' => $this->type,
            'units' => $this->whenLoaded('unit') ?? [],
            'quantity' => $this->quantity,
            'reserved_quantity' => $this->reserved_quantity,
            'minimum_quantity' => $this->minimum_quantity,
            'summary' => $this->getTranslations('summary'),
            'specification' => $this->getTranslations('specification'),
            'image' => $this->image && !empty($this->image) ?  getAssetsLink('storage/'.$this->image): 'default_image' ,
            'brand' => $this->whenLoaded('brand') ?? [],
            'tax' => $this->whenLoaded('tax') ?? [],
            'meta_title' => $this->getTranslations('meta_title'),
            'meta_description' => $this->getTranslations('meta_description'),
            'meta_keyword' => $this->getTranslations('meta_keyword'),
            'description' => $this->getTranslations('description'),
            'barcode' => $this->barcode,
            'height' => $this->height,
            'width' => $this->width,
            'length' => $this->length,
            'weight' => $this->weight,
            'is_disabled' => $this->is_disabled,
            'sort' => $this->sort,
            'parent_product' => $this->whenLoaded('parent') ?? [],
            'is_default_child' => $this->is_default_child,
            'product_status' => $this->whenLoaded('productStatus') ?? [],
            'is_show_related_product' => $this->is_show_related_product,
            'website_status' => $this->website_status,
            'pre_order' => $this->pre_order,
            'bundle_reserved_quantity' => $this->bundle_reserved_quantity,
            'categories' => $this->whenLoaded('category') ?? [],
            'fields' => $this->whenLoaded('field') ?? [],
            'images' => $this->whenLoaded('images') ?? [],
            'labels' => $this->whenLoaded('labels') ?? [],
            'prices' => $this->whenLoaded('price') ?? [],
            'related_products' => $this->whenLoaded('productRelatedChildren') ?? [],
            'tags' => $this->whenLoaded('tags') ?? [],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
