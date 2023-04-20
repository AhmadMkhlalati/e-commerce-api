<?php

namespace App\Http\Controllers\Prices;

use App\Http\Controllers\MainController;
use App\Http\Requests\price\PricesRequest;
use App\Http\Resources\Price\PriceResource;
use App\Http\Resources\Price\RestFullPriceResource;
use App\Http\Resources\Price\SelectPriceResource;
use App\Http\Resources\Price\SinglePriceResource;
use Illuminate\Http\Request;
use App\Models\Price\Price;

class PricesController extends MainController
{

    const OBJECT_NAME = 'objects.price';
    const relations = ['currency','products','originalPrice','originalPricesChildren'];
    /**
     * Display a listing of the resource.
     *
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \Exception
     */
    public function index(Request $request)
    {
        if ($request->method()=='POST') {

            $searchKeys=['id','name','percentage'];
            $searchRelationsKeys = ['parent' =>['parent_name' => 'name',]];
            return $this->getSearchPaginated(PriceResource::class, Price::class, $request, $searchKeys,self::relations,$searchRelationsKeys);
        }
        return $this->successResponsePaginated(PriceResource::class,Price::class,self::relations);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOriginalPrices()
    {

        $originalPrices = Price::with(['originalPrice','currency'])->where('is_virtual',0)->get();
        return $this->successResponse(
            'Success!',
            [
                'prices' => PriceResource::collection($originalPrices)
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PricesRequest $request)
    {
        $price = new Price();
        $price->name = ($request->name);
        $price->currency_id = $request->currency_id;
        $price->is_virtual = (bool)$request->is_virtual;

        if($request->is_virtual){
            $price->original_price_id = $request->original_price_id;
            $price->percentage = $request->percentage;
        }else{
            $price->original_price_id = null;
            $price->percentage = null;
        }

        if($price->save()){
            return $this->successResponse(
                __('messages.success.create',['name' => __(self::OBJECT_NAME)]),
                [
                    'price' => new SinglePriceResource($price->load(['originalPrice','currency']))
                ]
            );
        }

        return $this->errorResponse(
             __('messages.failed.create',['name' => __(self::OBJECT_NAME)])
        );

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Price $price)
    {
        return $this->successResponse(
            'Success!',
            [
                'price' => new SinglePriceResource($price->load(['originalPrice','currency']))
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Price $price
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(PricesRequest $request, Price $price)
    {

        $price->name = ($request->name);
        $price->currency_id = $request->currency_id;
        $price->is_virtual = (bool)$request->is_virtual;

        if($request->is_virtual){
            $price->original_price_id = $request->original_price_id;
            $price->percentage = $request->percentage;
        }else{
            $price->original_price_id = null;
            $price->percentage = null;
        }

        if($price->save()){
            return $this->successResponse(
                __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
                [
                    'price' => new SinglePriceResource($price->load(['originalPrice','currency']))
                ]
            );
        }

        return $this->errorResponse(
            __('messages.failed.create',['name' => __(self::OBJECT_NAME)])
        );

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Price $id)
    {
        //this module can't be destroyed
    }

    public function getPricesList(){
        $prices = Price::with('currency')->get();
        return SelectPriceResource::collection($prices);

    }

    public function getTableHeaders(){
        return $this->successResponse('Success!', ['headers' => __('headers.prices') ]);
    }
    public function getPricesData(){
        return $this->successResponsePaginated(RestFullPriceResource::class,Price::class,['originalPrice','currency']);
    }
}
