<?php

namespace App\Http\Controllers\Coupons;

use App\Http\Controllers\MainController;
use App\Http\Requests\Coupons\CouponRequest;
use App\Http\Requests\MainRequest;
use App\Http\Resources\Coupons\CouponResource;
use App\Http\Resources\Coupons\CouponSingleResource;
use App\Http\Resources\Coupons\RestFullCouponResource;
use App\Models\Category\Category;
use App\Models\Coupons\Coupon;
use App\Models\Currency\Currency;
use App\Models\Product\ProductStatus;
use App\Models\Unit\Unit;
use Illuminate\Http\Request;

class CouponsController extends MainController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    const RELATIONS = [];
    const OBJECT_NAME = [];

    public function index(Request $request)
    {

        if ($request->method()=='POST') {
            $searchKeys=['id','title','code','start_date','expiry_date','discount_percentage','discount_amount','min_amount'];
            $searchRelationsKeys = [];

            return $this->getSearchPaginated(CouponResource::class, Coupon::class,$request, $searchKeys,self::RELATIONS,$searchRelationsKeys);
        }
        return $this->successResponsePaginated(CouponResource::class,Coupon::class,self::RELATIONS);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        $defaultCurrency = Currency::query()->where('is_default',1)->first();

        return $this->successResponse(data:[
            'default_currency' => $defaultCurrency
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CouponRequest $request)
    {
        $coupon = new Coupon();
        $coupon->title = ($request->title);
        $coupon->code = $request->code;
        $coupon->start_date = $request->start_date;
        $coupon->expiry_date = $request->expiry_date;

        if($request->type == 'amount'){
            $coupon->discount_amount = $request->value;
            $coupon->discount_percentage = null;
        }else{
            $coupon->discount_amount =null;
            $coupon->discount_percentage = $request->value;
        }

        $coupon->min_amount = $request?->min_amount;
        $coupon->is_one_time = $request->is_one_time ?? 0;
        $coupon->is_used =0;

        if(!$coupon->save()){
            return $this->errorResponse('Sorry but the coupon was not created try again later!');
        }

        return $this->successResponse(data:[
            'coupon' => new CouponSingleResource($coupon)
        ]);




    }

    /**
     * Display the specified resource.
     *
     * @param  Coupon $coupon
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Coupon $coupon)
    {
        return $this->successResponse('The created was updated successfully',data:[
            'coupon' => new CouponSingleResource($coupon)
        ]);
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
     * @param  Coupon $coupon
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CouponRequest $request,Coupon $coupon)
    {

        $coupon->title = $request->title;
        $coupon->code = $request->code;
        $coupon->start_date = $request->start_date;
        $coupon->expiry_date = $request->expiry_date;

        if($request->type == 'amount'){
            $coupon->discount_amount = $request->value;
            $coupon->discount_percentage = null;
        }else{
            $coupon->discount_amount =null;
            $coupon->discount_percentage = $request->value;
        }

        $coupon->min_amount = $request?->min_amount;
        $coupon->is_one_time = $request->is_one_time ?? 0;
        $coupon->is_used =0;

        if(!$coupon->save()){
            return $this->errorResponse('Sorry but the coupon was not updated, try again later!');
        }

        return $this->successResponse('The Coupon was updated successfully',data:[
            'coupon' => new CouponSingleResource($coupon)
        ]);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getCouponByCode(Request $request,$code){
        $coupon = Coupon::whereRaw("BINARY `code`= ?",[$code])->first();
        if(is_null($coupon)){
            return $this->errorResponse('The Coupon is invalid',[
                'coupon_code' => $code
            ]);
        }
        $data = $coupon->checkIfCouponIsValid($request->amount);
        return $this->successResponse(data:$data);
    }

    public function getTableHeaders(){
        return $this->successResponse('Success!',['headers' => __('headers.coupons') ]);

    }

    public function getCouponsData(){
        return $this->successResponsePaginated(RestFullCouponResource::class,Coupon::class);
    }

}
