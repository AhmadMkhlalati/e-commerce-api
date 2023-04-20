<?php

namespace App\Http\Controllers\Language;

use App\Exceptions\FileErrorException;
use App\Http\Controllers\MainController;
use App\Http\Requests\Language\StoreLanguageRequest;
use App\Http\Resources\Language\LanguageResource;
use App\Http\Resources\Language\RestFullLanguageResource;
use App\Http\Resources\Language\SingleLanguageResource;
use App\Models\Language\Language;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;


class LanguageController extends MainController
{
    const OBJECT_NAME = 'objects.language';
    private $imagesPath = "";

    public function __construct($defaultPermissionsFromChild = null)
    {
        parent::__construct($defaultPermissionsFromChild);
        $this->imagesPath = Language::$imagesPath;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->method() == 'POST') {
            $searchKeys = ['id','name', 'code'];
            return $this->getSearchPaginated(LanguageResource::class, Language::class, $request, $searchKeys);
        }
        return $this->successResponsePaginated(LanguageResource::class, Language::class);
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
    public function store(StoreLanguageRequest $request)
    {
        //        $language=new Language();
        //        $language->name = (array)json_decode($request->name);
        //        $language->code=$request->code;
        //
        //        $language->is_default=false;
        //        if((bool)$request->is_default)
        //            $language->setIsDefault();
        //
        //        if($request->image){
        //            $language->image= $this->imageUpload($request->file('image'),$this->imagesPath['images']);
        //        }
        //
        //        if(!$language->save())
        //            return $this->errorResponse(
        //                __('messages.failed.create',['name' => __(self::OBJECT_NAME)])
        //            );
        //
        //        return $this->successResponse(
        //            __('messages.success.create',['name' => __(self::OBJECT_NAME)]),
        //            [
        //                'language' => new SingleLanguageResource($language)
        //            ]
        //        );


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Language $language)
    {
        return $this->successResponse('Success', ['language' => new SingleLanguageResource($language)]);
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
    public function update(StoreLanguageRequest $request, Language $language)
    {
        //
        //        $language->name =  (array) json_decode($request->name);
        //        $language->code=$request->code;
        //        $language->is_default=false;
        //        if((bool)$request->is_default)
        //            $language->setIsDefault();
        //
        //        if($request->image){
        //            if( !$this->removeImage($language->image) ){
        //                 throw new FileErrorException();
        //            }
        //            $language->image= $this->imageUpload($request->file('image'),$this->imagesPath['images']);
        //
        //         }
        //
        //
        //        if(!$language->save())
        //            return $this->errorResponse(
        //                __('messages.failed.update',['name' => __(self::OBJECT_NAME)])
        //            );
        //
        //        return $this->successResponse(
        //            __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
        //            [
        //                'language' => new SingleLanguageResource($language)
        //            ]
        //        );

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Language $language)
    {
        //        $defaultLanugage=Language::where('is_default',1)->first();
        //        if($defaultLanugage)
        //            return $this->errorResponse(
        //                __('messages.failed.delete',['name' => __(self::OBJECT_NAME)])
        //            );
        //
        //        if(!$language->delete())
        //            return $this->errorResponse(
        //                __('messages.failed.delete',['name' => __(self::OBJECT_NAME)])
        //            );
        //
        //        return $this->successResponse(
        //            __('messages.success.delete',['name' => __(self::OBJECT_NAME)]),
        //            [
        //                'language' => new SingleLanguageResource($language)
        //            ]
        //        );

    }
    public function setLanguage($locale)
    {

        $language = Language::where('code', $locale)->first();
        if (!$language)
            return $this->errorResponse(
                __('messages.failed.update', ['name' => __(self::OBJECT_NAME)])
            );

        App::setLocale($locale);
        if (App::getLocale() == $locale) {
            return $this->successResponse(
                __('messages.success.update', ['name' => __(self::OBJECT_NAME)]),
            );
        }

        return $this->errorResponse(
            __('messages.failed.update', ['name' => __(self::OBJECT_NAME)])
        );
    }
    public function toggleStatus(Request $request, $id)
    {

        $request->validate([
            'is_disabled' => 'boolean|required'
        ]);

        $language = Language::findOrFail($id);
        $language->is_disabled = (bool) $request->is_disabled;
        if (!$language->save())
            return $this->errorResponse(
                __('messages.failed.update', ['name' => __(self::OBJECT_NAME)])
            );

        return $this->successResponse(
            __('messages.success.update', ['name' => __(self::OBJECT_NAME)]),
            [
                'language' =>  new SingleLanguageResource($language)
            ]
        );
    }

    public function updateSortValues(Request $request)
    {

        $language = new Language();
        $order = $request->order;
        $index = 'id';

        batch()->update($language, $order, $index);

        return $this->successResponse(
            __('messages.success.update', ['name' => __(self::OBJECT_NAME)])
        );
    }
    public function setLanguageIsDefault($language)
    {

        $languageObject = Language::findOrFail($language);
        $languageObject->setIsDefault();
        $languageObject->save();

        return $this->successResponse(
            __('messages.success.update', ['name' => __(self::OBJECT_NAME)]),
            [
                'language' => new SingleLanguageResource($languageObject)
            ]
        );
    }
    public function getAllLanguagesSorted()
    {
        $languages = Language::order()->get();
        return $this->successResponse(
            'Success!',
            [
                'languages' => $languages
            ]
        );
    }

    public function getTableHeaders()
    {
        return $this->successResponse(
            'Success!',
            [
                'headers' => __('headers.languages')
            ]
        );
    }

    public function getLanguagesData()
    {
        return $this->successResponsePaginated(RestFullLanguageResource::class, Language::class);
    }
}
