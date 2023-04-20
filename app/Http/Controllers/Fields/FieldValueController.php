<?php

namespace App\Http\Controllers\Fields;

use App\Http\Controllers\MainController;
use App\Http\Requests\Field\StoreFieldsValueRequest;
use App\Http\Resources\Field\FieldsValueResource;
use App\Models\Field\Field;
use App\Models\Field\FieldValue;
use Exception;
use Illuminate\Http\Request;

class FieldValueController extends MainController
{
    const OBJECT_NAME = 'objects.fieldValue';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->method()=='POST') {
            $searchKeys=['field_id','value'];
            $data= $this->getSearchPaginated(FieldsValueResource::class, FieldValue::class,$request, $searchKeys);
            if($data->isEmpty()){
                $data=[
                   'data' => [
                       [
                       'id' => '',
                       'field'=>'',
                       'value'=> '',

                   ]
                   ]
               ];
               return response()->json($data);
               return  FieldsValueResource::collection($data);
           }
           return $data;
        }
        return $this->successResponsePaginated(FieldsValueResource::class,FieldValue::class);
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
    public function store(StoreFieldsValueRequest $request)
    {
        $fieldValue=new FieldValue();
        $fieldValue->field_id = $request->field_id;
        $fieldValue->value = json_encode($request->value);


        if(! $fieldValue->save())
            return $this->errorResponse(
                __('messages.failed.create',['name' => __(self::OBJECT_NAME)])
            );

        return $this->successResponse(
            __('messages.success.create',['name' => __(self::OBJECT_NAME)]),
            [
                'field_value' => new FieldsValueResource($fieldValue)
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(FieldValue $fieldValue)
    {

        return $this->successResponse(
            'Success!',
            [
                'field_value' => new FieldsValueResource($fieldValue)
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
     * @param  FieldValue  $fieldValue
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreFieldsValueRequest $request, FieldValue $fieldValue)
    {
        $fieldValue->field_id = $request->field_id;
        $fieldValue->value =json_encode($request->value);


        if(! $fieldValue->save())
            return $this->errorResponse(
                __('messages.failed.update',['name' => __(self::OBJECT_NAME)])
            );

        return $this->successResponse(
            __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
            [
                'field_value' => new FieldsValueResource($fieldValue)
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  FieldValue  $fieldValue
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(FieldValue $fieldValue)
    {
        if(! $fieldValue->delete())
            return $this->errorResponse(
                __('messages.failed.delete',['name' => __(self::OBJECT_NAME)])
            );

        return $this->successResponse(
            __('messages.success.delete',['name' => __(self::OBJECT_NAME)]),
            [
                'field_value' => new FieldsValueResource($fieldValue)
            ]
        );
    }
}
