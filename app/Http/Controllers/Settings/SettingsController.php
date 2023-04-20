<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\MainController;
use App\Http\Requests\Setting\StoreSettingRequest;
use App\Http\Resources\Setting\RestFullSettingResource;
use App\Http\Resources\Setting\SettingsResource;
use App\Http\Resources\Setting\SingleSettingResource;
use App\Models\Settings\Setting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SettingsController extends MainController
{

    const OBJECT_NAME = 'objects.setting';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->method() == 'POST') {
            $searchKeys = ['id','title', 'value'];
            return $this->getSearchPaginated(SettingsResource::class, Setting::class, $request, $searchKeys);
        }

        return $this->successResponsePaginated(SettingsResource::class, Setting::class);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     **/
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
    public function store(StoreSettingRequest $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Settings\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Settings\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        // // // // // // // //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Settings\Setting  $setting
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreSettingRequest $request, Setting $setting)
    {
        DB::beginTransaction();
        try {
            $value="";
            if($request->type=='multi-select' && gettype($request->value)=='array'){
                $value=implode(',',$request->value);
            }else{
                $value=$request->value;
            }
            $setting->value=$value;
            $setting->save();
            Cache::rememberForever(Setting::$cacheKey, function () {
                return Setting::all(['id','title','type','value']);
            });
            DB::commit();
            return $this->successResponse(
                __('messages.success.update', ['name' => __(self::OBJECT_NAME)],),
            );
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->errorResponse(
                __('messages.failed.update', ['name' => __(self::OBJECT_NAME)]). " The error message is : ". $ex->getMessage(),
            );

        }

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Settings\Setting  $setting
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Setting $setting)
    {
    }


    public function getTableHeaders()
    {
        return $this->successResponse('Success!', [
            'headers' => __('headers.settings'),
            'column_data' => [
                    'key',
                    'title',
                    'name',
                    'value',
            ]
        ]);
    }

    public function getSettingsData(){
        return $this->successResponsePaginated(RestFullSettingResource::class, Setting::class);
        }
}
