<?php

namespace App\Http\Controllers\Country;

use App\Exceptions\FileErrorException;
use App\Http\Controllers\MainController;
use App\Http\Requests\Countries\StoreCountryRequest;
use App\Http\Requests\Countries\UpdateCountryRequest;
use App\Http\Resources\Country\CountryResource;
use App\Http\Resources\Country\CoutnrySingleResource;
use App\Http\Resources\Country\RestFullCountryResource;
use App\Models\Country\Country;
use Illuminate\Http\Request;

class CountryController extends MainController
{
    const OBJECT_NAME = 'objects.country';
    private $imagesPath = "";

    public function __construct($defaultPermissionsFromChild = null)
    {
        $this->imagesPath = Country::$imagesPath;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->method() == 'POST') {

            $searchKeys = ['id','name', 'iso_code_1', 'iso_code_2', 'phone_code'];
            return $this->getSearchPaginated(CountryResource::class, Country::class, $request, $searchKeys);

        }
        return $this->successResponsePaginated(CountryResource::class, Country::class);
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCountryRequest $request)
    {

        $country = new Country();
        $dataTranslatable = (array)json_decode($request->name);
        $country->name = ($dataTranslatable);
        $country->iso_code_1 = $request->iso_code_1;
        $country->iso_code_2 = $request->iso_code_2;
        $country->phone_code = $request->phone_code;
        $country->flag = $request->flag;
        if ($request->hasFile('flag')) {
            $country->flag = $this->imageUpload($request->file('flag'), $this->imagesPath['images']);
        }
        if (!$country->save())
            return $this->errorResponse(__('messages.failed.create', ['name' => __(self::OBJECT_NAME)]));

        return $this->successResponse(
            __('messages.success.create', ['name' => __(self::OBJECT_NAME)]),
            [
                'country' => new CoutnrySingleResource($country)
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Country $country)
    {
        return $this->successResponse(
            'Success!',
            [
                'country' => new CoutnrySingleResource($country)
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCountryRequest $request, Country $country)
    {
        $dataTranslatable = (array)json_decode($request->name);
        $country->name = ($dataTranslatable);
        $country->iso_code_2 = $request->iso_code_2;
        $country->iso_code_1 = $request->iso_code_1;
        $country->phone_code = $request->phone_code;
        if ($request->flag) {

            if ($country->image) {
                if (!$this->removeImage($country->image)) {
                    throw new FileErrorException();
                }
            }
            $country->flag = $this->imageUpload($request->file('flag'), $this->imagesPath['images']);
        }
        if (!$country->save())
            return $this->errorResponse(
                __('messages.failed.update', ['name' => __(self::OBJECT_NAME)])
            );

        return $this->successResponse(
            __('messages.success.update', ['name' => __(self::OBJECT_NAME)]),
            [
                'country' => new CoutnrySingleResource($country)
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Country $country)
    {
        if (!$country->delete())
            return $this->errorResponse(
                __('messages.failed.delete', ['name' => __(self::OBJECT_NAME)])
            );

        return $this->successResponse(
            __('messages.success.delete', ['name' => __(self::OBJECT_NAME)]),
            [
                'country' => new CoutnrySingleResource($country)
            ]
        );
    }


    public function getTableHeaders()
    {
        return $this->successResponse('Success!', ['headers' => __('headers.countries')]);
    }

    public function getCountriesData()
    {
        return $this->successResponsePaginated(RestFullCountryResource::class, Country::class);
    }
}
