<?php

namespace App\Http\Controllers\Tax;

use App\Http\Controllers\MainController;
use App\Http\Requests\Tax\StoreTaxRequest;
use App\Http\Resources\Tax\RestFullTaxResource;
use App\Http\Resources\Tax\SingleTaxResource;
use App\Http\Resources\Tax\TaxResource;
use App\Models\Tax\Tax;
use App\Models\Tax\TaxComponent;
use App\Services\Tax\TaxsServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class TaxController extends MainController
{

    const OBJECT_NAME = 'objects.tax';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $relations=['taxComponents'];
        if ($request->method()=='POST') {
            $searchKeys=['id','name','percentage','complex_behavior'];
            return $this->getSearchPaginated(TaxResource::class, Tax::class,$request, $searchKeys,$relations);
        }
        return $this->successResponsePaginated(TaxResource::class,Tax::class,$relations);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        $taxes = Tax::all();
        return $this->successResponse('success!',[
            'components' =>  TaxResource::collection($taxes)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreTaxRequest $request)
    {

    $tax=new Tax();
    $tax->name = ($request->name);
        $tax->is_complex = (boolean)$request->is_complex;
        $tax->complex_behavior = $request->complex_behavior;

    if($request->is_complex){
        $tax->percentage = 0;
    }else{
        $tax->percentage = $request->percentage;
    }

    $check=true;

    if(!$tax->save())
        return $this->errorResponse(['message' => __('messages.failed.create',['name' => __(self::OBJECT_NAME)]) ]);

    if($request->is_complex && ($request->components != null || count($request->components) > 0)){
        TaxsServices::createComponentsForTax($request->components, $tax);

        }

        if(!$check)
            return $this->errorResponse(
                __('messages.failed.create',['name' => __(self::OBJECT_NAME)])
            );


    return $this->successResponse(
        __('messages.success.create',['name' => __(self::OBJECT_NAME)]),
        [
            'Taxes' => new SingleTaxResource($tax->load('taxComponents'))
        ]
    );

    return $this->errorResponse(__('messages.failed.create',['name' => __(self::OBJECT_NAME)]) );

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Tax $tax)
    {
        return $this->successResponse('Success' , ['tax' => new SingleTaxResource($tax->load('taxComponents'))]);
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
    public function update(StoreTaxRequest $request, Tax $tax)
    {
        DB::beginTransaction();
        try {

            TaxsServices::deleteRelatedTaxComponents($tax);

            $tax->name = ($request->name);
            $tax->is_complex = (boolean)$request->is_complex;
            if($request->is_complex){
                $tax->percentage = 0;
                $tax->complex_behavior = $request->complex_behavior;
            }
            else{
                $tax->complex_behavior = null;
                $tax->percentage = $request->percentage;
            }

            $tax->save();

            if($request->is_complex && ($request->components != null || count($request->components) > 0)){
                TaxsServices::createComponentsForTax($request->components, $tax);
            }

            DB::commit();
            return $this->successResponse(
                __('messages.success.create',['name' => __(self::OBJECT_NAME)]),
                [
                    'Taxes' => new SingleTaxResource($tax->load('taxComponents'))
                ]
            );

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(
                __('messages.failed.update',['name' => __(self::OBJECT_NAME)])
            );

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Tax $tax)
    {
        DB::beginTransaction();
        try {
            TaxsServices::deleteRelatedTaxComponents($tax);
            $tax->delete();

            DB::commit();
            return $this->successResponse(
                __('messages.success.delete',['name' => __(self::OBJECT_NAME)]),
                [
                    'taxes' => new SingleTaxResource($tax->load('taxComponents'))
                ]
            );

        }catch (\Exception $e){
            DB::rollBack();
            return $this->errorResponse(
                __('messages.failed.delete',['name' => __(self::OBJECT_NAME)])
            );

        }

    }

    public function getTableHeaders(){
        return $this->successResponse('Success',['headers' => __('headers.taxes') ]);
}

    public function getTaxesData(){
        return $this->successResponsePaginated(RestFullTaxResource::class,Tax::class,['taxComponents']);
    }
}
