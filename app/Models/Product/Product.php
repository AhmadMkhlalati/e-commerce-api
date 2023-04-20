<?php

namespace App\Models\Product;

use App\Models\Settings\Setting;
use App\Support\Str;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Category\Category;
use App\Models\Unit\Unit;
use App\Models\Tax\Tax;
use App\Models\Brand\Brand;
use App\Models\Price\Price;
use App\Models\Tag\Tag;
use App\Models\Product\ProductImage;
use App\Models\Label\Label;
use App\Models\Attribute\Attribute;
use App\Models\Attribute\AttributeValue;
use App\Models\Field\Field;
use App\Models\Field\FieldValue;
use App\Models\MainModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Spatie\Translatable\HasTranslations;
use Illuminate\Support\Facades\DB;

class Product extends MainModel
{
    use HasFactory, HasTranslations;
    protected array $translatable = ['name', 'summary', 'specification', 'description', 'meta_title', 'meta_description', 'meta_keyword'];
    protected $table = 'products';
    protected $guard_name = 'web';
    protected $appends = ['real_quantity'];
    protected $fillable = [
        'name',
        'slug',
        'code',
        'sku',
        'type',
        'quantity',
        'reserved_quantity',
        'summary',
        'specification',
        'meta_title',
        'meta_description',
        'meta_keyword',
        'description',
        'website_status',
        'barcode',
        'height',
        'width',
        'is_same_price_as_parent',
        'length',
        'weight',
        'is_default_child',
        'parent_product_id',
        'category_id',
        'unit_id',
        'brand_id',
        'tax_id',
        'products_statuses_id',
        'is_show_related_product',
    ];
    public static $imagesPath = [
        'images' => 'products/images',
    ];
    public static $prdouctTypes = 'normal,bundle,service,variable,variable_child';
    public static $productStatuses = 'draft,pending_review,published';

    public function parent()
    {
        return $this->belongsTo(Product::class, 'parent_product_id', 'id');
    }

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class, 'parent_product_id');
    }

    public function relatedProducts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductRelated::class, 'parent_product_id', 'id');
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'products_categories', 'product_id', 'category_id');
    }
    public function unit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
    public function tax(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Tax::class, 'tax_id', 'id');
    }
    public function brand(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function priceClass(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Price::class, 'products_prices', 'product_id', 'price_id');
    }

    public function pricesList(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductPrice::class, 'product_id', 'id');
    }

    public function price(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductPrice::class, 'product_id', 'id');
    }

    public function productRelatedParent()
    {
        return $this->belongsTo(ProductRelated::class, 'parent_product_id');
    }
    public function productRelatedChildren()
    {
        return $this->hasMany(ProductRelated::class, 'parent_product_id');
    }
    public function productImages()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function defaultCategory()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'products_tags', 'product_id', 'tag_id');
    }
    public function labels()
    {
        return $this->belongsToMany(Label::class, 'products_labels', 'product_id', 'label_id');
    }

    public function field()
    {
        return $this->belongsToMany(Field::class, 'products_fields', 'product_id', 'field_id');
    }
    // public function fieldValue(){
    //     return $this->hasMany(FieldValue::class,'id','');

    // }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function productStatus()
    {
        return $this->belongsTo(ProductStatus::class, 'products_statuses_id');
    }

    public function getNameAttribute($value){
        return Str::title($value);
    }

    public function getRealQuantityAttribute(): float{
        return $this->quantity - ($this->reserved_quantity + $this->bundle_reserved_quantity);
    }

    public function getVirtualPricing(Price | int $pricingClass)
    {
        $pricingClass  = is_int($pricingClass)  ?  Price::findOrFail($pricingClass) : $pricingClass;
        $originalPricingClass = $pricingClass->originalPrice;
        if (!$originalPricingClass) {
            return 0;
        }
        if (!$pricingClass->is_virtual) {
            return 0;
        }
        $originalPricingClassId = $originalPricingClass->id;
        $productPricing = ProductPrice::where('product_id', $this->id)->where('price_id', $originalPricingClassId)->first();
        if ($productPricing == null) {
            return 0;
        }
        return ($productPricing->price * $pricingClass->percentage) / 100.0;
    }

    public function getPrice(int $pricingClassId)
    {
        $pricingClass  = Price::findOrFail($pricingClassId);
        if ($pricingClass->is_virtual) {
            return $this->getVirtualPricing($pricingClassId);
        }
        $productPricing = ProductPrice::where('product_id', $this->id)->where('price_id', $pricingClassId)->first();
        return is_null($productPricing) ? 0 : $productPricing->price;
    }

    public function getRealQuantitsyAttribute()
    {
        return $this->quantity - ($this->reserved_quantity + $this->bundle_reserved_quantity);
    }

    //    protected function realQuantity(): Attribute
    //    {
    //        return Attribute::make(
    //            get: fn ($value) => $this->quantity - ($this->reserved_quantity + $this->bundle_reserved_quantity),
    ////            set: fn ($value) => strtolower($value),
    //        );
    //    }




    /**
     * @throws \Throwable
     */
    public function editQuantity(float $newQuantity)
    {
        if ($this->type == 'service' || $this->type == 'variable') {
            return $this;
        }
        $oldQuantity = $this->quantity;
        if ($this->type == 'bundle') {
            $oldQuantity = $this->reserved_quantity;
        }

        $differenceQuantity = $oldQuantity - $newQuantity;

        if ($differenceQuantity > 0) {
            return $this->updateProductQuantity($differenceQuantity, 'sub');
        }
        return $this->updateProductQuantity(abs($differenceQuantity), 'add');
    }

    /**
     * @throws Exception
     * @throws \Throwable
     */
    public function updateProductQuantity(float $quantity, string $method, bool $isOrder = false)
    {
        if ($method != 'add' && $method != 'sub') {
            throw new \Exception('Bad method type ' . $method);
        }
        if ($this->type == 'service' || $this->type == 'variable') {
            return $this;
        }
        if ($this->type == 'normal' || $this->type == 'variable_child') {
            if ($method == 'add') {
                return $this->addQuantityForNormalAndVariableChild($quantity);
            } else {
                return $this->subQuantityForNormalAndVariableChild($quantity);
            }
        }
        if ($this->type == 'bundle') {
            $allBundleRelatedProducts = $this->relatedProducts;
            //                $allBundleRelatedProducts = ProductRelated::query()->where('parent_product_id', $this->id)->get();
            $allBundleProducts = self::query()->findMany($allBundleRelatedProducts->pluck('child_product_id')->toArray());
            if ($method == 'add') {
                return $this->addQuantityForBundle($quantity, $allBundleProducts, $allBundleRelatedProducts, $isOrder);
            } else {
                return $this->subQuantityForBundle($quantity, $allBundleProducts, $allBundleRelatedProducts, $isOrder);
            }
        }


        throw new Exception('The type of product is invalid ' . $this->type);
    }

    protected function addQuantityForNormalAndVariableChild(float $quantity)
    {
        $this->quantity += $quantity;
        if ($this->save())
            return $this;

        throw new \Exception('An error occurred please try again !');
    }

    /**
     * @throws Exception
     */
    protected function subQuantityForNormalAndVariableChild(float $quantity)
    {
        $isAllowNegativeQuantity = getSettings('allow_negative_quantity')->value;
        if ($isAllowNegativeQuantity || $this->pre_order) {
            $this->quantity -= $quantity;
            if ($this->save())
                return $this;

            throw new \Exception('An error occurred please try again !');
        }
        $realQuantity = $this->quantity - ($this->reserved_quantity + $this->bundle_reserved_quantity);
        if ($realQuantity < $quantity) {
            throw new Exception('You have less quantity than ' . $quantity . ' in stock');
        }

        $this->quantity -= $quantity;
        if ($this->save())
            return $this;

        throw new \Exception('An error occurred please try again !');
    }

    /**
     * @throws Exception
     */
    private function addQuantityForBundle(float $quantity, Collection $allBundleProducts, Collection $allBundleRelatedProducts, bool $isOrder = false): self
    {

        if ($this->type != 'bundle') {
            throw new Exception('Calling addQuantityForBundle methode on non bundle product.');
        }

        if (!$isOrder) {
            // not an order this means that a new bundle is being added to the database
            if (!$this->hasEnoughRelatedProductsQuantityForReservingNewBundles($quantity, $allBundleProducts, $allBundleRelatedProducts))
                throw new Exception('Not enough quantity for reserving a new bundle');
        }

        $this->reserved_quantity += $quantity;

        foreach ($allBundleProducts as $bundleProduct) {

            if ($bundleProduct->type == 'service') {
                continue;
            }

            $bundleRelatedProduct = $allBundleRelatedProducts->where('child_product_id', $bundleProduct->id)->where('parent_product_id', $this->id)->first();
            $bundleProduct->bundle_reserved_quantity += $quantity * $bundleRelatedProduct['child_quantity'];

            if ($isOrder) {
                $bundleProduct->quantity += $quantity * $bundleRelatedProduct['child_quantity'];
            }

            if (!$bundleProduct->save()) {
                throw new Exception("Error while saving the product with id {$bundleProduct->id} please try again later");
            }
        }

        if (!$this->save()) {
            throw new Exception('Error while saving the product please try again later');
        }
        return $this;
    }

    /**
     * @throws Exception
     * @throws \Throwable
     * This function is used when we are planning to remove a bundle from the system
     */
    private function subQuantityForBundle(float $quantity, Collection $allBundleProducts = null, Collection $allRelatedProducts = null, bool $isOrder = false): self
    {
        if (!$this->hasEnoughRelatedProductsQuantityForSubstitutingBundles($quantity, $allBundleProducts, $allRelatedProducts, $isOrder)) {
            throw new Exception('Not enough quantity or substituting or buying bundles');
        }
        $allowNegativeQuantity = getSettings('allow_negative_quantity')->value;
        if (!$allowNegativeQuantity && $this->reserved_quantity < $quantity) {
            throw new Exception('Not enough quantity for reserving a new bundle');
        }

        $this->reserved_quantity -= $quantity;

        foreach ($allBundleProducts as $bundleProduct) {
            if ($bundleProduct->type == 'service' || $bundleProduct->is_pre_order) {
                continue;
            }
            $bundleRelatedProduct = $allRelatedProducts->where('child_product_id', $bundleProduct->id)->first();

            $bundleProduct->bundle_reserved_quantity -= $quantity * $bundleRelatedProduct->child_quantity;

            if ($isOrder) {
                $bundleProduct->quantity -= $quantity * $bundleRelatedProduct->child_quantity;
            }
        }

        if (!$bundleProduct->save()) {
            throw new Exception("Error in saving product of id {$bundleProduct->id} !");
        }



        if (!$this->save())
            throw new Exception("Error in saving product, please try again later");


        return $this;
    }

    /**
     * @throws Exception
     *
     */
    public function hasEnoughRelatedProductsQuantityForReservingNewBundles(float $quantity, Collection $allProducts, Collection $allRelatedProducts): bool
    {

        //this function is for

        if ($this->type != 'bundle') {
            throw new Exception('Call hasEnoughRelatedProductsQuantityForReservingNewBundles on non-bundle product');
        }
        $isAllowNegativeQuantity = getSettings('allow_negative_quantity')->value;
        if ($isAllowNegativeQuantity) {
            return true;
        }

        $relatedProducts = ($allRelatedProducts)->where('parent_product_id', $this->id);
        $relatedProductsIds = $relatedProducts->pluck('child_product_id');
        $childrenProducts = collect($allProducts)->whereIn('id', $relatedProductsIds);
        foreach ($childrenProducts as $childProduct) {

            if ($childProduct['pre_order'] || $childProduct['type'] == 'service') {
                continue;
            }

            $childRelatedProduct = $relatedProducts
                ->where('child_product_id', $childProduct['id'])
                ->where('parent_product_id', $this->id)
                ->first();

            if ($childProduct['bundle_reserved_quantity'] < 0) {
                // to ignore any problems with the calculations
                $childProduct['bundle_reserved_quantity'] = 0;
            }

            $childProduct['quantity'] -= $childProduct['bundle_reserved_quantity'] + $childProduct['reserved_quantity'];

            $quantityToBeReserved = $childRelatedProduct['child_quantity'] * $quantity;
            if ($quantityToBeReserved > $childProduct['quantity']) {
                return false;
            }
        }
        return true;
    }

    /**
     * @throws \Throwable
     */
    private function hasEnoughRelatedProductsQuantityForSubstitutingBundles($quantity, $allProducts, $allRelatedProducts, bool $isOrder = false): bool
    {
        if ($this->type != 'bundle') {
            throw new Exception('Call hasEnoughRelatedProductsQuantityForSubstitutingBundles on non-bundle product');
        }

        $isAllowNegativeQuantity = getSettings('allow_negative_quantity')->value;
        if ($isAllowNegativeQuantity) {
            return true;
        }

        $allProducts = count($allProducts) > 0 ? $allProducts : self::all();
        $allRelatedProducts = count($allRelatedProducts) > 0 ? $allRelatedProducts : ProductRelated::all();

        $relatedProducts = collect($allRelatedProducts)->where('parent_product_id', $this->id);
        $relatedProductsIds = $relatedProducts->pluck('child_product_id');
        $childrenProducts = collect($allProducts)->whereIn('id', $relatedProductsIds);
        foreach ($childrenProducts as $childProduct) {
            if ($childProduct['pre_order']) {
                continue;
            }

            $childRelatedProduct = $relatedProducts
                ->where('child_product_id', $childProduct['id'])
                ->where('parent_product_id', $this->id)
                ->first();

            if ($childProduct['bundle_reserved_quantity'] < 0) {
                $childProduct['bundle_reserved_quantity'] = 0;
            }
            $reservedQuantityFromOtherBundles = $childProduct['bundle_reserved_quantity'] - ($this->reserved_quantity * $childRelatedProduct['child_quantity']);
            $childProduct['quantity'] -= $childProduct['reserved_quantity'] + $reservedQuantityFromOtherBundles;
            $quantityToBeReserved = $childRelatedProduct['child_quantity'] * $quantity;

            if ($quantityToBeReserved > $childProduct['quantity']) {
                return false;
            }
        }
        return true;
    }

    //    public function recalculateBundleReservedQuantity( array $allProducts = [], array $allRelatedProducts = []){
    //        $allProducts = count($allProducts) > 0 ? $allProducts : self::all();
    //        $allRelatedProducts = count($allRelatedProducts) > 0 ? $allRelatedProducts : ProductRelated::all();
    //
    //
    //        $relatedProducts = collect($allRelatedProducts)->where('parent_product_id' ,$this->id)->get();
    //        $relatedProductsIds = $relatedProducts->pluck('id');
    //        $products = $allProducts->whereIn('id',$relatedProductsIds)->get();
    //
    //        foreach ($products as $product){
    //
    //        }
    //    }


}
