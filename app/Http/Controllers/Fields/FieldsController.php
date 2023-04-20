<?php

namespace App\Http\Controllers\Fields;

use App\Http\Controllers\MainController;
use App\Http\Requests\Field\StoreFieldRequest;
use App\Http\Resources\Field\FieldHeaderResource;
use App\Http\Resources\Field\FieldsResource;
use App\Http\Resources\Field\RestFullFieldResource;
use App\Http\Resources\Field\SingleFieldResource;
use App\Models\Field\Field;
use App\Models\Field\FieldValue;
use App\Services\Field\FieldService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FieldsController extends MainController
{
    const OBJECT_NAME = 'objects.field';
    const relations = ['fieldValue'];

    public function __construct($defaultPermissionsFromChild = null)
    {
        parent::__construct($defaultPermissionsFromChild);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->method()=='POST') {
            $searchKeys=['id','title','type','entity','is_required'];
            return $this->getSearchPaginated(FieldHeaderResource::class, Field::class,$request, $searchKeys,self::relations);

        }
        return $this->successResponsePaginated(FieldHeaderResource::class,Field::class,self::relations);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreFieldRequest $request)
    {
        DB::beginTransaction();
        try {
        $field=new Field();
        $field->title = ($request->title);
        $field->type = $request->type;
        $field->entity = $request->entity;
        $field->is_required =  (bool)$request->is_required;
        $field->is_attribute =  (bool)$request->is_attribute;

        if(!$field->save())
          return $this->errorResponse(__('messages.failed.create',['name' => __(self::OBJECT_NAME)]));

        $check=true;

          if($request->type=='select' && $request->field_values){
             FieldService::addFieldValuesToField($request->field_values,$field);
          }

          DB::commit();
          return $this->successResponse(
            __('messages.success.create',['name' => __(self::OBJECT_NAME)]),
            [
                'field' => new SingleFieldResource($field->load('fieldValue'))
            ]
        );
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse(__('messages.failed.create',['name' => __(self::OBJECT_NAME)]));
        }

        }



    /**
     * Display the specified resource.
     *
     * @param  Field  $field
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Field $field)
    {
        return $this->successResponse(
            'Success!',
            [
                'field' => new SingleFieldResource($field->load('fieldValue'))
            ]
        );

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Field  $field
     * @return \Illuminate\Http\Response
     */
    public function edit(Field $field)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Field  $field
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreFieldRequest $request, Field $field)
    {
        DB::beginTransaction();
        try {
            FieldService::deleteRelatedfieldValues($field);

            $field->title = ($request->title);
            $field->type = $request->type;
            $field->entity = $request->entity;
            $field->is_required =  (bool)$request->is_required;
            $field->is_attribute =  (bool)$request->is_attribute;
            $field->save();

            if ($request->type == 'select' && $request->field_values) {
                FieldService::addFieldValuesToField($request->field_values, $field);
            }

                DB::commit();
                return $this->successResponse(
                    __('messages.success.update', ['name' => __(self::OBJECT_NAME)]),
                    [
                        'field' => new SingleFieldResource($field->load('fieldValue'))
                    ]
                );
            }
        catch(\Exception $e) {
                DB::rollBack();
                return $this->errorResponse(
                    __('messages.failed.update', ['name' => __(self::OBJECT_NAME)])
                );

            }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Field $field)
    {
        DB::beginTransaction();
        try {
            FieldService::deleteRelatedfieldValues($field);
            $field->delete();

            DB::commit();
            return $this->successResponse(
                __('messages.success.delete',['name' => __(self::OBJECT_NAME)]),
                [
                    'field' => new SingleFieldResource($field->load('fieldValue'))
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
        return $this->successResponse('Success!' , ['headers' => __('headers.fields') ]);
    }

    public function getFieldsData(){
        return $this->successResponsePaginated(RestFullFieldResource::class,Field::class,['fieldValue']);
    }
}
