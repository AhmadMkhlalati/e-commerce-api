<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Brand\SelectBrandResource;
use App\Http\Resources\Brand\SingleBrandResource;
use App\Http\Resources\Category\SelectCategoryResource;
use App\Http\Resources\Category\SingleCategoryResource;
use App\Http\Resources\Field\FieldsResource;
use App\Http\Resources\Field\SelectFieldResource;
use App\Http\Resources\Field\SingleFieldResource;
use App\Http\Resources\Label\SelectLabelResource;
use App\Http\Resources\Label\SingleLableResource;
use App\Http\Resources\Price\SelectPriceResource;
use App\Http\Resources\Tag\TagResource;
use App\Http\Resources\Tax\SelectTaxResource;
use App\Http\Resources\Tax\SingleTaxResource;
use App\Http\Resources\Unit\SelectUnitResource;
use App\Http\Resources\Unit\SingleUnitResource;
use App\Models\Category\Category;
use App\Models\Field\Field;
use App\Models\Field\FieldValue;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use App\Models\Product\ProductField;
use App\Models\Product\ProductImage;
use App\Models\Product\ProductRelated;
use App\Services\Category\CategoryService;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleProductResource extends JsonResource
{

    public function __construct($product, ...$data)
    {
        $this->productRelated = $data[0];
        $this->relatedProducts = $data[1];
        $this->relatedProductsImages = $data[2];
        $this->relatedProductsPrices = $data[3];
        $this->productsFields = $data[4];
        $this->productsAttributes = $data[5];
        $this->childrenFieldValues = $data[6];
        $this->childrenImages = $data[7];
        $this->resource = $product;
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $selectedCategoriesIds = $this->whenLoaded('category');
        $nestedCategories = CategoryService::getAllCategoriesNested($this->all_categories, $selectedCategoriesIds->pluck('id')->toArray());
        // $childrenIds = Product::where('parent_product_id', $this->id)->pluck('id')->toArray();
        // $productAttributes = ProductField::whereIn('product_id', $childrenIds)->get();
        // dd($this->whenLoaded('tags'));

        return [
            'id' => (int)$this->id,
            'name' => $this->getTranslations('name'),
            'slug' => $this->slug,
            'category' => $this->whenLoaded('defaultCategory') ? new SelectCategoryResource($this->whenLoaded('defaultCategory')) : [],
            'code' => $this->code,
            'sku' => $this->sku,
            'type' => $this->type,
            'unit' => $this->whenLoaded('unit') ? new SelectUnitResource($this->whenLoaded('unit')) : [],
            'quantity' => (float)$this->quantity,
            'reserved_quantity' => (float)$this->reserved_quantity,
            'minimum_quantity' => (float)$this->minimum_quantity,
            'summary' => $this->getTranslations('summary') ?? [],
            'specification' => $this->getTranslations('specification')  ?? [],
            'image' => $this->image && !empty($this->image) ?  getAssetsLink('storage/' . $this->image) : 'default_image',
            'image_path' => $this->image ?? 'default_image',
            'brand' => $this->whenLoaded('brand') ? new SelectBrandResource($this->whenLoaded('brand')) : [],
            'tax' => $this->whenLoaded('tax') ? new SelectTaxResource($this->whenLoaded('tax')) : [],
            'meta_title' => $this->getTranslations('meta_title')  ?? [],
            'meta_description' => $this->getTranslations('meta_description')  ?? [],
            'meta_keyword' => $this->getTranslations('meta_keyword')  ?? [],
            'description' => $this->getTranslations('description')  ?? [],
            'barcode' => $this->barcode ?? null,
            'height' => (float)$this->height ?? 0,
            'width' => (float)$this->width ?? 0,
            'length' => (float)$this->length ?? 0,
            'weight' => (float)$this->weight ?? 0,
            'is_disabled' => (bool)$this->is_disabled,
            'sort' => (int)$this->sort,
            'parent_product_id' => (int)$this->parent_product_id ?? null,
            'is_default_child' => (bool)$this->is_default_child,
            'products_statuses_id' => (int)$this->products_statuses_id,
            'is_show_related_product' => (bool)$this->is_show_related_product,
            'website_status' => $this->website_status,
            'pre_order' => (int)$this->pre_order ?? 0,
            'bundle_price_status' => $this->bundle_price_status,
            'prices' => ProductPriceResoruce::collection($this->whenLoaded('price')->load('prices.currency')) ?? [],
            'fields' => SingleFieldResource::collection($this->whenLoaded('field'))->where('is_attribute', 0) ?? [],
            'attributes' => ProductAttributesResource::collection($this->productsAttributes),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'labels' => SelectLabelResource::collection($this->whenLoaded('labels')),
            'categories' => $nestedCategories,
            'related_products' => ProductRelatedResource::customCollection($this->productRelated, $this->relatedProducts, $this->relatedProductsImages, $this->relatedProductsPrices->load('prices')) ?? [],
            'variations' => ProductVariableResoruce::customCollection($this->whenLoaded('children'), $this->childrenFieldValues, $this->childrenImages),
            'images' => ProductImagesResource::collection($this->whenLoaded('images')) ?? [],
            'products_fields' => ProductFieldsResource::collection($this->productsFields),
        ];
    }
}
