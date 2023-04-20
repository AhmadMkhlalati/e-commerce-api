<?php

namespace App\Http\Controllers\Currency;

use App\Exceptions\FileErrorException;
use App\Http\Controllers\MainController;
use App\Http\Requests\Currency\StoreCurrencyRequest;
use App\Http\Resources\Currency\CurrencyHistoryResource;
use App\Http\Resources\Currency\CurrencyResource;
use App\Http\Resources\Currency\IndexCurrencyResource;
use App\Http\Resources\Currency\RestFullCurrencyResource;
use App\Http\Resources\Currency\SingleCurrencyResource;
use App\Models\Currency\Currency;
use App\Models\Currency\CurrencyHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

class CurrencyController extends MainController
{
    const OBJECT_NAME = 'objects.currency';
    const relations = ['currencyHistory'];
    private $imagesPath = "";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->imagesPath = Currency::$imagesPath;
    }
    public function index(Request $request)
    {
        if ($request->method() == 'POST') {
            $searchKeys = ['id', 'name', 'code', 'symbol', 'rate'];
            return $this->getSearchPaginated(IndexCurrencyResource::class, Currency::class, $request, $searchKeys, self::relations);
        }
        return $this->successResponsePaginated(IndexCurrencyResource::class, Currency::class, self::relations);
    }

    public function getCurrencyHistories()
    {

        return $this->successResponsePaginated(CurrencyHistoryResource::class, CurrencyHistory::class);
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
    public function store(StoreCurrencyRequest $request)
    {

        $currency = new Currency();
        $currency->name = (array)json_decode($request->name);
        $currency->code = $request->code;
        $currency->symbol = $request->symbol;
        $currency->rate = $request->rate;
        $currency->is_default = false;
        if ((bool)$request->is_default)
            $currency->setIsDefault();

        if ($request->image) {
            $currency->image = $this->imageUpload($request->file('image'), $this->imagesPath['images']);
        }

        if (!$currency->save())
            return $this->errorResponse(__('messages.failed.create', ['name' => __(self::OBJECT_NAME)]));

        return $this->successResponse(
            __('messages.success.create', ['name' => __(self::OBJECT_NAME)]),
            [
                'currency' => new SingleCurrencyResource($currency)
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Currency $currency)
    {
        return $this->successResponse(
            'Success!',
            [
                'currency' => new SingleCurrencyResource($currency)
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
    public function update(StoreCurrencyRequest $request, Currency $currency)
    {
        DB::beginTransaction();

        try {
            $currency->name = (array)json_decode($request->name);
            $currency->code = $request->code;
            $currency->symbol = $request->symbol;
            $currency->rate = $request->rate;
            $currency->is_default = false;
            if ((bool)$request->is_default)
                $currency->setIsDefault();

            if ($request->image) {
                if (!$this->removeImage($currency->image)) {
                    throw new FileErrorException();
                }
                $currency->image = $this->imageUpload($request->file('image'), $this->imagesPath['images']);
            }

            $currency->save();
            DB::commit();

            return $this->successResponse(
                __('messages.success.update', ['name' => __(self::OBJECT_NAME)]),
                [
                    'currency' => new SingleCurrencyResource($currency)
                ]
            );
        } catch (\Exception $exception) {
            DB::rollBack();

            return $this->errorResponse(
                __('messages.failed.update', ['name' => __(self::OBJECT_NAME)]),
                [
                    $exception->getMessage()
                ]
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Currency $currency)
    {
        //        if(!$currency->delete()){
        //            return response()->json([
        //                'data' => [
        //                    'message' => 'The currency was not deleted ! please try again later',
        //                ]
        //            ],512);
        //        }
        //
        //        return response()->json([
        //            'data' => [
        //                'message' => 'currency deleted successfully',
        //                'currency' => new CurrencyResource($currency)
        //            ]
        //
        //        ],201);
    }
    public function setCurrencyIsDefault($currency)
    {

        $currencyObject = Currency::findOrFail($currency);
        $currencyObject->setIsDefault();
        $currencyObject->save();

        return $this->successResponse(
            __('messages.success.update', ['name' => __(self::OBJECT_NAME)]),
            [
                'currency' => new SingleCurrencyResource($currencyObject)
            ]
        );
    }
    public function getTableHeaders()
    {
        return $this->successResponse(
            'Success!',
            [
                'headers' => __('headers.currencies')
            ]
        );
    }
    public function getCurrenciesData()
    {
        return $this->successResponsePaginated(RestFullCurrencyResource::class, Currency::class, ['currencyHistory']);
    }
}
