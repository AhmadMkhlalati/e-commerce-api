<?php

namespace App\Http\Controllers\Unit;

use App\Http\Controllers\MainController;
use App\Http\Requests\Unit\StoreUnitRequest;
use App\Http\Resources\Unit\RestFullUnitResource;
use App\Http\Resources\Unit\SingleUnitResource;
use App\Http\Resources\Unit\UnitResource;
use App\Models\Unit\Unit;
use Exception;
use Illuminate\Http\Request;

class UnitController extends MainController
{
    const OBJECT_NAME = 'objects.unit';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->method()=='POST') {
            $searchKeys=['id','name','code'];
            return $this->getSearchPaginated(UnitResource::class, Unit::class,$request, $searchKeys);
        }
        return $this->successResponsePaginated(UnitResource::class,Unit::class);

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
    public function store(StoreUnitRequest $request)
    {
        $unit=new Unit();
        $unit->name = $request->name;
        $unit->code=$request->code;

        if(!$unit->save())
            return $this->errorResponse(__('messages.failed.create',['name' => __(self::OBJECT_NAME)]));

        return $this->successResponse(
            __('messages.success.create',['name' => __(self::OBJECT_NAME)]),
            [
                'unit' => new SingleUnitResource($unit)
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Unit $unit)
    {
        return $this->successResponse('Success!' , ['unit' => new SingleUnitResource($unit)]);
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
    public function update(StoreUnitRequest $request, Unit $unit)
    {
        $unit->name=($request->name);
        $unit->code=$request->code;


        if(!$unit->save())
            return $this->errorResponse(
                __('messages.failed.update',['name' => __(self::OBJECT_NAME)])
            );

        return $this->successResponse(
            __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
            [
                'unit' => new SingleUnitResource($unit)
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Unit $unit)
    {
        if(!$unit->delete())
            return $this->errorResponse(
                __('messages.failed.delete',['name' => __(self::OBJECT_NAME)])
            );

        return $this->successResponse(
            __('messages.success.delete',['name' => __(self::OBJECT_NAME)]),
            [
                'unit' => new SingleUnitResource($unit)
            ]
        );

    }

    public function getTableHeaders(){
        return $this->successResponse('Success!',['headers' => __('headers.units') ]);
}

    public function getUnitsData(){
        return $this->successResponsePaginated(RestFullUnitResource::class,Unit::class);
    }
}
