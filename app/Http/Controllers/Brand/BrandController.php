<?php

namespace App\Http\Controllers\Brand;

use App\Exceptions\FileErrorException;
use App\Http\Controllers\MainController;
use App\Http\Requests\Brand\StoreBrandRequest;
use App\Http\Resources\Brand\BrandResource;
use App\Http\Resources\Brand\HxaBrandResource;
use App\Http\Resources\Brand\RestFullBrandResource;
use App\Http\Resources\Brand\SingleBrandResource;
use App\Http\Resources\Field\FieldsResource;
use App\Http\Resources\Label\LabelsResource;
use App\Models\Brand\Brand;
use App\Models\Brand\BrandField;
use App\Models\Brand\BrandLabel;
use App\Models\Field\Field;
use App\Models\Label\Label;
use App\Services\Brand\BrandsService;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BrandController extends MainController
{
    const OBJECT_NAME = 'objects.brands';
    private $imagesPath = "";
    public function __construct($defaultPermissionsFromChild = null)
    {
        $this->imagesPath = Brand::$imagesPath;
        //    parent::__construct(['BrancController@index' => ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->method() == 'POST') {
            $searchKeys = ['id','name', 'code', 'meta_title'];
            return $this->getSearchPaginated(BrandResource::class, Brand::class, $request, $searchKeys);
        }

        return $this->successResponsePaginated(BrandResource::class, Brand::class);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        $fields = Field::with('fieldValue')->whereEntity('brand')->get();
        $labels = Label::whereEntity('brand')->get();

        return $this->successResponse(
            'Success!',
            [
                'fields' =>  FieldsResource::collection($fields),
                'labels' => LabelsResource::collection($labels)
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreBrandRequest $request)
    {
        DB::beginTransaction();
        try {

            $brand = new Brand();

            if (gettype($request->name) != 'array') {
                $brand->name = (array)json_decode($request->name);
            } else {
                $brand->name = $request->name;
            }
            $brand->code = '0';

            if ($request->image)
                $brand->image = $this->imageUpload($request->file('image'), $this->imagesPath['images']);

            if (gettype($request->meta_title) != 'array') {
                $brand->meta_title = (array)json_decode($request->meta_title);
            } else {
                $brand->meta_title = $request->meta_title;
            }

            if (gettype($request->meta_description) != 'array') {
                $brand->meta_description = (array)json_decode($request->meta_description);
            } else {
                $brand->meta_description = $request->meta_description;
            }

            if (gettype($request->meta_keyword) != 'array') {
                $brand->meta_keyword = (array)json_decode($request->meta_keyword);
            } else {
                $brand->meta_keyword = $request->meta_keyword;
            }

            if (gettype($request->description) != 'array') {
                $brand->description = (array)json_decode($request->description);
            } else {
                $brand->description = $request->description;
            }

            $brand->save();
            //End of Brand Store

            $brand->code = $brand->id;

            $brand->save();

            //Fields Store
            if ($request->has('fields')) {
                BrandsService::addFieldsToBrands($brand, ($request->fields));
            }

            if ($request->has('labels')) {
                $oldLabel = $request->labels;
                if (gettype($request->labels) == 'string') {
                    $request->labels = explode(",", $request->labels);
                    if (count($request->labels) <= 0) {
                        $request->labels = $oldLabel;
                    }
                }
                BrandsService::addLabelsToBrands($brand, $request->labels);
            }

            DB::commit();
            return $this->successResponse(
                __('messages.success.create', ['name' => __(self::OBJECT_NAME)]),
                [
                    'brands' => new SingleBrandResource($brand->load(['label', 'field', 'fieldValue']))
                ]
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(
                __('messages.failed.create', ['name' => __(self::OBJECT_NAME)]) . " error message : $e",
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Brand $brand)
    {
        return $this->successResponse("success!", ['brands' => new SingleBrandResource($brand->load(['field', 'label', 'fieldValue']))]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Brand  $brand
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreBrandRequest $request, Brand $brand)
    {

        DB::beginTransaction();
        try {

            BrandsService::deleteRelatedBrandFieldsAndLabels($brand);

            if (gettype($request->name) != 'array') {
                $brand->name = (array)json_decode($request->name);
            } else {
                $brand->name = $request->name;
            }

            if ($request->image) {
                if (!$this->removeImage($brand->image)) {
                    throw new FileErrorException();
                }
                $brand->image = $this->imageUpload($request->file('image'), $this->imagesPath['images']);
            }

            if (gettype($request->meta_title) != 'array') {
                $brand->meta_title = (array)json_decode($request->meta_title);
            } else {
                $brand->meta_title = $request->meta_title;
            }

            if (gettype($request->meta_description) != 'array') {
                $brand->meta_description = (array)json_decode($request->meta_description);
            } else {
                $brand->meta_description = $request->meta_description;
            }

            if (gettype($request->meta_keyword) != 'array') {
                $brand->meta_keyword = (array)json_decode($request->meta_keyword);
            } else {
                $brand->meta_keyword = $request->meta_keyword;
            }

            if (gettype($request->description) != 'array') {
                $brand->description = (array)json_decode($request->description);
            } else {
                $brand->description = $request->description;
            }

            $brand->save();


            if ($request->has('fields')) {
                BrandsService::addFieldsToBrands($brand, $request->fields);
            }
            $oldLabel = $request->labels;

            if ($request->has('labels')) {
                if (gettype($request->labels) == 'string') {
                    $request->labels = explode(",", $request->labels);
                    if (count($request->labels) <= 0) {
                        $request->labels = $oldLabel;
                    }
                }
                BrandsService::addLabelsToBrands($brand, $request->labels);
            }

            DB::commit();

            return $this->successResponse(
                __('messages.success.update', ['name' => __(self::OBJECT_NAME)]),
                [
                    'brands' => new SingleBrandResource($brand->load(['label', 'field', 'fieldValue']))
                ]
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(
                __('messages.failed.update', ['name' => __(self::OBJECT_NAME)]) . "the error message: $e",
            );
        } catch (Error $error) {
            DB::rollBack();
            return $this->errorResponse(
                __('messages.failed.create', ['name' => __(self::OBJECT_NAME)]) . "the error message: $error",
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Brand  $brand
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Brand $brand)
    {
        DB::beginTransaction();
        try {
            BrandsService::deleteRelatedBrandFieldsAndLabels($brand);
            $brand->delete();
            DB::commit();
            return $this->successResponse(
                __('messages.success.delete', ['name' => __(self::OBJECT_NAME)]),

            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(__('messages.failed.delete', ['name' => __(self::OBJECT_NAME)]));
        }
    }
    public function toggleStatus(Request $request, $id)
    {

        $request->validate([
            'is_disabled' => 'boolean|required'
        ]);

        $brand = Brand::findOrFail($id);
        $brand->is_disabled = $request->is_disabled;
        if (!$brand->save())
            return $this->errorResponse(__('messages.failed.update', ['name' => __(self::OBJECT_NAME)]));

        return $this->successResponse(
            __('messages.success.update', ['name' => __(self::OBJECT_NAME)]),
            [
                'brands' =>  new BrandResource($brand)
            ]
        );
    }

    public function getAllBrandsSorted()
    {
        $brands = Brand::order()->get();
        return $this->successResponse('Success!', ['brands' => $brands]);
    }


    public function updateSortValues(Request $request)
    {

        batch()->update($brand = new Brand(), $request->order, 'id');

        return $this->successResponsePaginated(BrandResource::class, Brand::class);
    }

    public function getTableHeaders(): \Illuminate\Http\JsonResponse
    {
        return $this->successResponse('success', ['headers' => __('headers.brands')]);
    }

    public function getBrandsData()
    {
        return $this->successResponsePaginated(RestFullBrandResource::class, Brand::class, ['field', 'label']);
    }
}
