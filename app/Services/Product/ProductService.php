<?php

namespace App\Services\Product;

use App\Models\Field\Field;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use App\Models\Product\ProductField;
use App\Models\Product\ProductImage;
use App\Models\Product\ProductLabel;
use App\Models\Product\ProductPrice;
use App\Models\Product\ProductRelated;
use App\Models\Product\ProductTag;
use App\Services\Category\CategoryService;
use Carbon\Carbon;
use DateTime;
use Error;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ProductService
{
    private $imagesPath = "";
    private $fieldTypes = "";
    public function __construct()
    {
        $this->imagesPath = Product::$imagesPath;
        $this->fieldTypes = Field::$fieldTypes;
    }
    public function storeAdditionalProductData($request, $product, $childrenIds)
    {


        $this->storeAdditionalCategrories($request, $product, $childrenIds)
            ->storeAdditionalFields($request, $product)
            ->removeAdditionalImages($request)
            ->storeAdditionalImages($request, $product)
            ->storeAdditionalLabels($request, $product, $childrenIds)
            ->storeAdditionalTags($request, $product, $childrenIds)
            ->storeAdditionalPrices($request, $product, $childrenIds)
            ->storeAdditionalAttributes($request, $product);
    }
    public function storeAdditionalCategrories($request, $product, $childrenIds)
    {
        //        DB::beginTransaction();
        try {

            $childrenIdsArray = $childrenIds;
            $childrenIdsArray[] = $product->id;

            if (!$request->has('categories'))
                return $this;

            if (is_null($request->categories))
                return $this;

            $categoryCheck = ProductCategory::where('product_id', $product->id)->orWhereIn('product_id', $childrenIds)->delete();

            $categoriesIdsArray = [];
            $oneLevelCategoryArray = CategoryService::loopOverMultiDimentionArray($request->categories);
            foreach ($childrenIdsArray as $key => $child) {
                foreach ($oneLevelCategoryArray as $key => $category) {
                    $isChecked = filter_var($category['checked'], FILTER_VALIDATE_BOOLEAN);
                    if ($isChecked) {
                        $categoriesIdsArray[] = [
                            'product_id' => $child,
                            'category_id' => $category['id'],
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];
                    }
                }
            }
            ProductCategory::insert($categoriesIdsArray);
            //            DB::commit();
            return $this;
        } catch (Exception $e) {
            //            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
    public function storeAdditionalFields($request, $product)
    {
        //        DB::beginTransaction();
        try {
            if (!array_key_exists('fields', $request->toArray())) {
                return $this;
            }

            if (count($request['fields']) == 0) {
                return $this;
            }
            $fieldCheck = ProductField::where('product_id', $product->id)->delete();
            $data = [];

            foreach ($request->fields as $index => $field) {
                if (!in_array($field['type'], $this->fieldTypes))
                    throw new Exception('Invalid fields type');

                if ($field['type'] == 'select') {
                    throw_if(!is_numeric($field['value']), new Exception('Invalid value'));
                    $data[] = [
                        'product_id' => $product->id,
                        'field_id' => (int)$field['field_id'],
                        'field_value_id' =>  (int)$field['value'],
                        'value' => null,
                    ];
                } elseif (($field['type']) == 'checkbox') {
                    throw_if(!is_bool($field['value']), new Exception('Invalid value'));
                    $data[] = [
                        'product_id' => $product->id,
                        'field_id' => (int)$field['field_id'],
                        'field_value_id' =>  null,
                        'value' => (bool)$field['value'],
                    ];
                } elseif (($field['type']) == 'date') {
                    throw_if(Carbon::createFromFormat('Y-m-d H:i:s', $field['value']) !== false, new Exception('Invalid value'));
                    $data[] = [
                        'product_id' => $product->id,
                        'field_id' => (int)$field['field_id'],
                        'field_value_id' =>  null,
                        'value' => Carbon::createFromFormat('Y-m-d H:i:s', $field['value']),
                    ];
                } elseif (($field['type']) == 'text' || gettype($field['type']) == 'textarea') {
                    $data[] = [
                        'product_id' => $product->id,
                        'field_id' => (int)$field['field_id'],
                        'field_value_id' =>  null,
                        'value' => ($field['value']),
                    ];
                } else {
                    continue;
                }
                $allData = $data;
            }
            ProductField::insert($data);
            //            DB::commit();
            return $this;
        } catch (Exception $e) {
            //            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
    public function storeAdditionalAttributes($request, $product)
    {
        //        DB::beginTransaction();
        try {
            if (!array_key_exists('attributes', $request->toArray())) {
                return $this;
            }

            if (count($request['attributes']) == 0) {
                return $this;
            }
            $attributesCheck = ProductField::where('product_id', $product->id)->delete();

            $data = [];
            $allData = [];
            //            $attributes = $request->has('product_variations') ? (collect($request->toArray()['product_variations'])->pluck('attributes')->first()) : [];
            //            $attributesUnique = (collect($attributes)->unique(fn($item) => $item['value'] . $item['field_id'] ));
            foreach ($request['attributes'] as $index => $attribute) {
                if (!in_array($attribute['type'], $this->fieldTypes))
                    throw new Exception('Invalid attribute type');

                if ($attribute['type'] == 'select') {
                    throw_if(!is_numeric($attribute['value']), new Exception('Invalid value'));
                    $data[] = [
                        'product_id' => $product->id,
                        'field_id' => (int)$attribute['field_id'],
                        'field_value_id' =>  (int)$attribute['value'],
                        'is_used_for_variations' =>  1,
                        'value' => null,
                    ];
                } elseif (($attribute['type']) == 'checkbox') {
                    throw_if(!is_bool($attribute['value']), new Exception('Invalid value'));
                    $data[] = [
                        'product_id' => $product->id,
                        'field_id' => (int)$attribute['field_id'],
                        'field_value_id' =>  null,
                        'value' => (bool)$attribute['value'],
                    ];
                } elseif (($attribute['type']) == 'date') {
                    throw_if(Carbon::createFromFormat('Y-m-d H:i:s', $attribute['value']) !== false, new Exception('Invalid value'));
                    $data[] = [
                        'product_id' => $product->id,
                        'field_id' => (int)$attribute['field_id'],
                        'field_value_id' =>  null,
                        'value' => Carbon::createFromFormat('Y-m-d H:i:s', $attribute['value']),
                    ];
                } elseif (($attribute['type']) == 'text' || gettype($attribute['type']) == 'textarea') {
                    $data[] = [
                        'product_id' => $product->id,
                        'field_id' => (int)$attribute['field_id'],
                        'field_value_id' =>  null,
                        'value' => ($attribute['value']),
                    ];
                } else {

                    continue;
                }
            }
            ProductField::insert($data);
            //            DB::commit();
            return $this;
        } catch (Exception $e) {
            //            DB::rollBack();
            throw new Exception($e);
        } catch (Error $e) {
            //            DB::rollBack();
            throw new Exception($e);
        }
    }
    public function removeAdditionalImages($request)
    {

        if (!$request->has('images_deleted') || is_null($request->images_deleted))
            return $this;

        if (!ProductImage::whereIn('id', $request->images_deleted)->delete()) {
            throw new Exception('Error while deleting product images');
        }
        return $this;
    }
    public function storeAdditionalImages($request, $product)
    {
        //$request=(object)$request;
        //        DB::beginTransaction();
        try {
            if (!$request->has('images') || is_null($request->images)) {
                return $this;
            }
            if (count($request->images) != count($request->images_data)) {
                throw new Exception('Images and images_data count is not equal');
            }

            $data = [];
            foreach ($request->images as $index => $image) {
                $imagePath = "";
                if ($request->file('images') && !is_string($request->file('images')))
                    $imagePath = uploadImage($image, $this->imagesPath['images']);

                $data[] = [
                    'product_id' => $product->id,
                    'image' => $imagePath,
                    'title' => json_encode($request->images_data[$index]['title']),
                    'sort' => $request->images_data[$index]['sort'],
                    'created_at'  => Carbon::now()->toDateString(),
                    'updated_at' => Carbon::now()->toDateString(),
                ];
            }


            ProductImage::insert($data);
            //            DB::commit();
            return $this;
        } catch (Exception $e) {
            //            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
    public function storeAdditionalLabels($request, $product, $childrenIds)
    {
        //$request=(object)$request;
        //        DB::beginTransaction();
        try {
            $labelCheck = ProductLabel::where('product_id', $product->id)->orWhereIn('product_id', $childrenIds)->delete();

            if (!$request->has('labels') || is_null($request->has('labels')))
                return $this;


            $childrenIdsArray = $childrenIds;
            $childrenIdsArray[] = $product->id;

            $data = [];

            foreach ($childrenIdsArray as $key => $child) {
                foreach ($request->labels as $index => $label) {
                    $data[] = [
                        'product_id' => $child,
                        'label_id' => $label,
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString()
                    ];
                }
            }

            ProductLabel::insert($data);
            //            DB::commit();
            return $this;
        } catch (Exception $e) {
            //            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
    public function storeAdditionalTags($request, $product, $childrenIds)
    {
        //$request=(object)$request;
        //        DB::beginTransaction();
        try {
            $tagCheck = ProductTag::where('product_id', $product->id)->orWhereIn('product_id', $childrenIds)->delete();

            if (!$request->has('tags') || is_null($request->tags))
                return $this;


            $childrenIdsArray = $childrenIds;
            $childrenIdsArray[] = $product->id;

            $data = [];

            foreach ($childrenIdsArray as $key => $child) {
                foreach ($request->tags as $index => $tag) {
                    $data[] = [
                        'product_id' => $child,
                        'tag_id' => $tag,
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString()
                    ];
                }
            }

            ProductTag::insert($data);
            //            DB::commit();
            return $this;
        } catch (Exception $e) {
            //            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
    // TYPE BUNDLE
    public function storeAdditionalBundle($request, $product)
    {
        //$request=(object)$request;
        $bundleCheck = ProductRelated::where('parent_product_id', $product->id)->delete();
        $pricesArray = [];
        if ($request->type == 'bundle') {
            foreach ($request->related_products as $related_product => $value) {

                $childNameStatus = array_key_exists('child_name_status', $value) ? $value['child_name_status'] : 'default';
                $name = null;
                if ($childNameStatus == 'hide')
                    $name = null;
                elseif ($childNameStatus == 'default')
                    $name = json_encode($request->name);
                elseif ($childNameStatus == 'custom')
                    $name = array_key_exists('name', $value) ? json_encode($value['name']) : null;

                $data[$related_product] = [
                    'parent_product_id' => $product->id,
                    'child_product_id' => $value['child_product_id'],
                    'child_name_status' => array_key_exists('child_name_status', $value) ? $value['child_name_status'] : 'default',
                    'name' =>  $name,
                    'child_quantity' => $value['child_quantity'],
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                ];
            }
            ProductRelated::insert($data);
        }
        return $this;
    }

    // END OF TYPE BUNDLE
    public function storeAdditionalPrices($request, $product)
    {
        //        DB::beginTransaction();
        try {
            if (!$request->has('prices') || is_null($request->prices))
                return $this;


            ProductPrice::where('product_id', $product->id)->delete();

            $pricesArray =  [];

            foreach ($request->prices as $price => $value) {
                $pricesArray[$price]["product_id"] = $product->id;
                $pricesArray[$price]["price_id"] = $value['price_id'];
                $pricesArray[$price]["price"] = $value['price'];
                $pricesArray[$price]["discounted_price"] = array_key_exists('discounted_price', $value) ? $value['discounted_price'] : 0;
                $pricesArray[$price]["created_at"] = Carbon::now()->toDateTimeString();
                $pricesArray[$price]["updated_at"] = Carbon::now()->toDateTimeString();
            }
            ProductPrice::insert($pricesArray);
            //            DB::commit();
            return $this;
        } catch (Exception $e) {
            //            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
    public static function deleteRelatedDataForProduct(Product $product)
    {

        //        DB::beginTransaction();
        try {
            $data = [
                ProductCategory::class,
                ProductField::class,
                ProductImage::class,
                ProductLabel::class,
                ProductPrice::class,
                ProductTag::class,
                ProductPrice::class,
            ];

            $productType = $product->type ?? '';
            if ($productType == 'variable') {

                if (!$product->has('children'))
                    return;

                $productChildren = $product->children->pluck('id');
                foreach ($data as $table) {
                    $table::whereIn('product_id', $productChildren)->delete();
                }
                Product::whereIn('id', $productChildren)->delete();
            } elseif ($productType == 'bundle') {

                $product->updateProductQuantity($product->reserved_quanityt, 'sub');
                ProductRelated::where('parent_product_id', $product->id)->delete();
            } else {

                foreach ($data as $table)
                    $table::where('product_id', $product->id)->delete();
            }
            Product::where('id', $product->id)->delete();


            //DB::commit();
        } catch (\Exception $e) {
            //DB::rollBack();
            throw new Exception($e);
        }
    }
    // TYPE VARIABLE
    public function storeFieldsForVariations($fieldsArray, $childrenIds)
    {
        //        DB::beginTransaction();
        try {
            if (is_null($fieldsArray)  || count($fieldsArray) == 0)
                return $this;

            $fieldCheck = ProductField::whereIn('product_id', $childrenIds)->whereHas('field', fn ($query) => $query->where('is_attribute', 0))->delete();
            $allData = [];
            $data = [];

            foreach ($childrenIds as $key => $child) {
                foreach ($fieldsArray as $index => $field) {
                    if (!in_array($field['type'], $this->fieldTypes))
                        throw new Exception('Invalid fields type');

                    if ($field['type'] == 'select') {
                        throw_if(!is_numeric($field['value']), new Exception('Invalid value'));
                        $data[] = [
                            'product_id' => $child,
                            'field_id' => (int)$field['field_id'],
                            'field_value_id' =>  (int)$field['value'],
                            'value' => null,
                        ];
                    } elseif (($field['type']) == 'checkbox') {
                        throw_if(!is_bool($field['value']), new Exception('Invalid value'));
                        $data[] = [
                            'product_id' => $child,
                            'field_id' => (int)$field['field_id'],
                            'field_value_id' =>  null,
                            'value' => (bool)$field['value'],
                        ];
                    } elseif (($field['type']) == 'date') {
                        throw_if(Carbon::createFromFormat('Y-m-d H:i:s', $field['value']) !== false, new Exception('Invalid value'));
                        $data[] = [
                            'product_id' => $child,
                            'field_id' => (int)$field['field_id'],
                            'field_value_id' =>  null,
                            'value' => Carbon::createFromFormat('Y-m-d H:i:s', $field['value']),
                        ];
                    } elseif (($field['type']) == 'text' || gettype($field['type']) == 'textarea') {
                        $data[] = [
                            'product_id' => $child,
                            'field_id' => (int)$field['field_id'],
                            'field_value_id' =>  null,
                            'value' => ($field['value']),
                        ];
                    } else {
                        continue;
                    }

                    $allData[] = $data;
                }
            }
            ProductField::query()->insert($allData);

            //            DB::commit();
            return $this;
        } catch (Exception $e) {
            //            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
    public function storeAttributesForVariations($attributesArray, $childrenIds)
    {
        try {
            if (is_null($attributesArray) || count($attributesArray) == 0)
                return $this;
            //TODO : handel this types of functions
            $attributesCheck = ProductField::whereIn('product_id', $childrenIds)->whereHas('field', fn ($query) => $query->where('is_attribute', 1))->delete();
            $data = [];
            foreach ($childrenIds as $key => $child) {
                foreach ($attributesArray[$key] as $index => $attribute) {
                    if (!in_array($attribute['type'], $this->fieldTypes))
                        throw new Exception('Invalid fields type');

                    if ($attribute['type'] == 'select') {
                        throw_if(!is_numeric($attribute['value']), new Exception('Invalid value'));
                        $data[] = [
                            'product_id' => $child,
                            'field_id' => (int)$attribute['field_id'],
                            'field_value_id' =>  (int)$attribute['value'],
                            'value' => null,
                        ];
                    }
                }
            }
            ProductField::insert($data);
            //            DB::commit();
            return $this;
        } catch (Exception $e) {
            //            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
    public function removeImagesForVariations($imagesDeletedArray, $childrenIds)
    {

        //        DB::beginTransaction();
        try {
            if (is_null($imagesDeletedArray))
                return $this;

            $imagesIdsArray = [];
            foreach ($childrenIds as $key => $child) {
                foreach ($imagesDeletedArray as $key => $value) {
                    $imagesIdsArray = $value[$key];
                }
            }
            ProductImage::whereIn('id', $imagesIdsArray)->delete();
            //            DB::commit();
            return $this;
        } catch (Exception $e) {
            //            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function storeImagesForVariations($imagesArray, $imagesData, $childrenIds)
    {
        //        DB::beginTransaction();
        try {
            if (is_null($imagesArray) || is_null($imagesData))
                return $this;

            $data = [];
            foreach ($childrenIds as $key => $child) {
                foreach ($imagesArray[$key] as $index => $image) {
                    $imagePath = uploadImage($image, $this->imagesPath['images']);
                    $data[] = [
                        'product_id' => $child,
                        'image' => $imagePath,
                        'title' => json_encode($imagesData[$key][$index]['title']),
                        'sort' => $imagesData[$key][$index]['sort'],
                        'created_at'  => Carbon::now()->toDateString(),
                        'updated_at' => Carbon::now()->toDateString(),
                    ];
                }
            }


            ProductImage::insert($data);
            //            DB::commit();
            return $this;
        } catch (Exception $e) {
            //            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
    public function storePricesForVariations($request, $childrenIds)
    {
        //        DB::beginTransaction();
        try {
            $data = [];
            foreach ($request->product_variations as $variation) {
                $pricesInfo = $variation['isSamePriceAsParent'] ? $request->prices : ($variation['prices'] ?? []);
            }
            if (is_null($pricesInfo)) {
                return $this;
            }
            $childrenIdsArray = $childrenIds;
            $data = [];
            foreach ($childrenIdsArray as $key => $child) {
                foreach ($pricesInfo as $key => $price) {
                    $data[] = [
                        'product_id' => $child,
                        'price_id' => $price['price_id'],
                        'price' => $price['price_id'],
                        'discounted_price' => $price['discounted_price'],
                        'created_at' =>  Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ];
                }
            }
            ProductPrice::Insert($data);
            //            DB::commit();
            return $this;
        } catch (Exception $e) {
            //            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @throws \Throwable
     */
    public function storeVariations($request, $product)
    {
        //        DB::beginTransaction();
        try {
            throw_if(count($request->product_variations) == 0, Exception::class, 'No variations found');

            $productVariationParentsArray = [];
            $imagesDeletedArray = [];
            $imagesArray = [];
            $fieldsArray = [];
            $attributesArray = [];

            $defaultChild = null;

            foreach ($request->product_variations as $variation) {
                $imagePath = array_key_exists('image', $variation) ? $variation['image'] : "";
                if (!is_null($variation['image'])) {
                    if ($variation['image'] && !is_string($variation['image'])) {
                        $imagePath = uploadImage($variation['image'],  $this->imagesPath['images']);
                    } else {
                        $imagePath = "";
                    }
                }
                if ($variation['is_default_child']) {
                    $defaultChild = $variation;
                }
                $isSameDimensionsAsParent =  array_key_exists('is_same_dimensions_as_parent', $variation) ? $variation['is_same_dimensions_as_parent'] : false;
                $height = "";
                $width = "";
                $length = "";
                $weight = "";

                if ($isSameDimensionsAsParent) {
                    $height = array_key_exists('height', $variation) ? $variation['height'] : null;
                    $width = array_key_exists('width', $variation) ? $variation['width'] : null;
                    $length = array_key_exists('p_length', $variation) ? $variation['p_length'] : null;
                    $weight = array_key_exists('weight', $variation) ? $variation['weight'] : null;
                } else {
                    $height =  $request->height ?? null;
                    $width = $request->width ?? null;
                    $length =  $request->p_length  ?? null;
                    $weight = $request->weight ?? null;
                }
                $productVariationsArray = [
                    'name' => json_encode($request->name),
                    'code' => $variation['code'],
                    'type' => 'variable_child',
                    'sku' => array_key_exists('sku', $variation) ? $variation['sku'] : null,
                    'quantity' => $variation['quantity'],
                    'is_same_price_as_parent' => $variation['isSamePriceAsParent'],
                    'reserved_quantity' => $variation['reserved_quantity'],
                    'minimum_quantity' => $variation['minimum_quantity'],
                    'height' => $height,
                    'width' => $width,
                    'length' => $length,
                    'weight' => $weight,
                    'barcode' => array_key_exists('barcode', $variation) ? $variation['barcode'] : null,
                    'category_id' => $request->category_id,
                    'unit_id' => $request->unit_id ?? null,
                    'tax_id' => $request->tax_id ?? null,
                    'brand_id' => $request->brand_id ?? null,
                    'summary' => json_encode($request->summary) ?? null,
                    'specification' => json_encode($request->specification) ?? null,
                    'meta_title' => json_encode($request->meta_title) ?? null,
                    'meta_keyword' => json_encode($request->meta_keyword) ?? null,
                    'meta_description' => json_encode($request->meta_description) ?? null,
                    'description' => json_encode($request->description) ?? null,
                    'website_status' => $request->website_status,
                    'parent_product_id' => $product->id,
                    'products_statuses_id' =>  array_key_exists('products_statuses_id', $variation) ? $variation['products_statuses_id'] : null,
                    'image' => $imagePath,
                    'is_show_related_product' => $variation['is_show_related_product'] ?? null,
                    'bundle_reserved_quantity' => null,
                    'pre_order' => array_key_exists('pre_order', $variation) ? $variation['pre_order'] : false,
                    'bundle_price_status' => array_key_exists('bundle_price_status', $variation) ? $variation['bundle_price_status'] : null

                ];

                $imagesDeletedArray = array_key_exists('images_deleted', $variation) ?  $variation['images_deleted'] : [];
                $imagesArray[] = array_key_exists('images', $variation) ? $variation['images'] : [];
                $imagesData[] = array_key_exists('images_data', $variation) ? $variation['images_data'] : [];
                //$fieldsArray[] = array_key_exists('fields', $variation) ? $variation['fields'] : [];
                $fieldsArray = [];
                $attributesArray[] = array_key_exists('attributes', $variation) ? $variation['attributes'] : [];
                $productVariationParentsArray[] = $productVariationsArray;
            }
            $model = new Product();
            Product::query()->upsert($productVariationParentsArray, 'id', $model->getFillable());

            $childrenIds = Product::query()->where('parent_product_id', $product->id)->get()->pluck('id');


            // set default child as default child
            if (!is_null($defaultChild)) {
                Product::query()->where('code', $defaultChild['code'])->update([
                    'is_default_child' => 1
                ]);
            }


            $this->removeImagesForVariations($imagesDeletedArray, $childrenIds);
            $this->storeImagesForVariations($imagesArray, $imagesData, $childrenIds);
            $this->storePricesForVariations($request, $childrenIds);
            $this->storeFieldsForVariations($fieldsArray, $childrenIds);
            $this->storeAttributesForVariations($attributesArray, $childrenIds);

            //            DB::commit();
            return $childrenIds;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    // END OF TYPE VARIABLE
    public function createAndUpdateProduct($request, $product = null)
    {
        //        DB::beginTransaction();
        try {
            //$request=(object)$request;
            $product = $product ?  $product : new Product();
            if (is_null($product->id)) {
                $product->type = $request->type;
            }
            $product->name = ($request->name);
            $product->slug = $request->slug;
            $product->code = $request->code;
            $product->sku = $request->sku ?? null;
            if (!$product->type == 'bundle') {
                $product->quantity = 0;
                $diffrenceQuantity = $request->quantity - ($product->reserved_quantity + $product->bundle_reserved_quantity);
                if ($diffrenceQuantity > ($request->reserved_quantity - $product->reserved_quantity)) {
                    $product->reserved_quantity = $request->reserved_quantity;
                }
            } else {
                $product->quantity = $request->quantity;
                $product->reserved_quantity = $request->reserved_quantity;
            }
            $product->minimum_quantity = $request->minimum_quantity;
            $product->summary = ($request->summary) ?? null;
            $product->specification = ($request->specification) ?? null;

            $product->meta_title = $request->meta_title ?? null;
            $product->meta_keyword = $request->meta_keyword ?? null;
            $product->meta_description = $request->meta_description ?? null;
            $product->description = $request->description ?? null;
            $product->website_status = $request->website_status;
            $product->barcode = $request->barcode ?? null;
            $product->height = $request->height ?? null;
            $product->width = $request->width ?? null;
            $product->is_disabled = 0;
            $product->length = $request->p_length ?? null;
            $product->weight = $request->weight ?? null;
            $product->is_default_child = $request->is_default_child ?? 0;
            $product->parent_product_id = $request->parent_product_id ?? null;
            $product->category_id = $request->category_id;
            $product->unit_id = $request->unit_id ?? null;
            $product->brand_id = $request->brand_id ?? null;
            $product->tax_id = $request->tax_id ?? null;
            $product->products_statuses_id = $request->products_statuses_id;
            $product->is_show_related_product = $request->is_show_related_product ?? null;
            $product->pre_order = $request->pre_order ?? null;
            $product->bundle_reserved_quantity = null;
            $product->bundle_price_status = $request->bundle_price_status ?? null;

            if ($request->file('image') && !is_string($request->file('image')))
                $product->image = uploadImage($request->image, $this->imagesPath['images']);

            $product->save();

            //            DB::commit();
            return $product;
        } catch (Exception $e) {
            //            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
