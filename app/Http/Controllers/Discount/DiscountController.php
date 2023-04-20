<?php

namespace App\Http\Controllers\Discount;

use App\Http\Controllers\MainController;
use App\Http\Requests\Discount\StoreDiscountRequest;
use App\Http\Resources\Discount\DiscountResource;
use App\Models\Brand\Brand;
use App\Models\Category\Category;
use App\Models\Discount\Discount;
use App\Models\Tag\Tag;
use App\Services\Discounts\DiscountsServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiscountController extends MainController
{
    const OBJECT_NAME = 'objects.discount';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->method()=='POST') {

            $searchKeys=['id','name','start_date','end_date','discount_percentage'];
            return $this->getSearchPaginated(DiscountResource::class, Discount::class,$request, $searchKeys);

        }
        return $this->successResponsePaginated(DiscountResource::class,Discount::class);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreDiscountRequest $request)
    {
        DB::beginTransaction();

        try {

            $discount = new Discount();
            $discount->name = json_encode($request->name);
            $discount->start_date = $request->start_date;
            $discount->end_date = $request->end_date;
            $discount->discount_percentage = $request->discount_percentage;

//            $discount->save();
            $tagsProducts =  DiscountsServices::extractProductsFromMultiArray( Tag::findMany($request->tag)->load('products')->pluck('products')->toArray() );
            $brandsProducts =DiscountsServices::extractProductsFromMultiArray( Brand::findMany($request->brand)->load('products')->pluck('products')->toArray() );

            $categorySingleProducts = DiscountsServices::extractProductsFromMultiArray( Category::findMany($request->category)->load('multipleProducts')->pluck('products')->toArray() );
            $categoryMultipleProducts = DiscountsServices::extractProductsFromMultiArray(Category::findMany($request->category)->load('products')->pluck('products')->toArray());

            $products = DiscountsServices::filterProducts($tagsProducts,$brandsProducts,$categorySingleProducts,$categoryMultipleProducts, $request->filter_type );

            return $this->successResponse(
                __('messages.success.create',['name' => __(self::OBJECT_NAME),]),
                [
                    'discount' => new DiscountResource($discount),
                    'products' => $products,
                ]
            );
            DB::commit();

        }catch (\Exception $e){
            DB::rollBack();
            return $this->errorResponse(['message' => __('messages.failed.create',['name' => __(self::OBJECT_NAME)]) ]);

        }




    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Discount $discount)
    {
        return $this->successResponse(
            'Success!',
            [
                'discount' => new DiscountResource($discount)
            ]
        );

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreDiscountRequest $request, Discount $discount)
    {
        $discount->name = json_encode($request->name);
        $discount->start_date = $request->start_date;
        $discount->end_date = $request->end_date;
        $discount->discount_percentage = $request->discount_percentage;

        if(!($discount->save()))
            return $this->errorResponse(
                __('messages.failed.update',['name' => __(self::OBJECT_NAME)])
        );

        return $this->successResponse(
            __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
            [
                'discount' => new DiscountResource($discount)
            ]
    );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Discount $discount)
    {
        if(!$discount->delete())
        return $this->errorResponse(
            __('messages.failed.delete',['name' => __(self::OBJECT_NAME)])
        );

     return $this->successResponse(
         __('messages.success.delete',['name' => __(self::OBJECT_NAME)]),
         [
            'discount' => new DiscountResource($discount)
         ]
     );
    }

    public function getTableHeaders(){
        return $this->successResponse('Success' , ['headers' => __('headers.discounts') ]);
}
}
