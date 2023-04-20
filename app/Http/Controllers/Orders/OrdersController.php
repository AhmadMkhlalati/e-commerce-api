<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\MainController;
use App\Http\Requests\Orders\StoreOrderRequest;
use App\Http\Resources\Country\SelectContryResource;
use App\Http\Resources\Currency\SelectCurrencyResource;
use App\Http\Resources\Customers\SelectCustomerResource;
use App\Http\Resources\Orders\OrderResource;
use App\Http\Resources\Orders\RestFullOrderResource;
use App\Http\Resources\Orders\SingelOrdersResource;
use App\Http\Resources\roles\RolesResource;
use App\Models\Country\Country;
use App\Models\Coupons\Coupon;
use App\Models\Currency\Currency;
use App\Models\Currency\CurrencyHistory;
use App\Models\Orders\OrderNote;
use App\Models\Orders\OrderProduct;
use App\Models\Orders\OrderStatus;
use App\Models\Price\Price;
use App\Models\Product\Product;
use App\Models\Product\ProductPrice;
use App\Models\RolesAndPermissions\CustomRole;
use App\Models\Settings\Setting;
use App\Models\Tax\Tax;
use App\Models\Tax\TaxComponent;
use App\Models\User\Customer;
use App\Models\User\CustomerAddress;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Resources\Orders\SelectOrderStatus;
use App\Models\Orders\Order;
use App\Services\Orders\OrdersService;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;

class OrdersController extends MainController
{
    const OBJECT_NAME = 'objects.role';
    const relations = ['customer'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->method() == 'POST') {
            $searchKeys = ['id','code', 'time', 'date', 'total'];
            $searchRelationsKeys = ['customer' => ['customer_first_name' => 'first_name', 'customer_last_name' => 'last_name']];
            return $this->getSearchPaginated(OrderResource::class, Order::class, $request, $searchKeys, self::relations, $searchRelationsKeys);
        }

        return $this->successResponsePaginated(OrderResource::class, Order::class, self::relations);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        $defaultCurrency = Currency::query()->where('is_default', 1)->firstOrFail();
        return $this->successResponse(data: [
            'countries' => SelectContryResource::collection(Country::query()->select(['id', 'name', 'iso_code_1'])->get()),
            'currencies' => SelectCurrencyResource::collection(Currency::all()),
            'default_currency' => (int)$defaultCurrency->id,
            'statuses' => SelectOrderStatus::collection(OrderStatus::query()->select(['id', 'name'],)->get()),
            'customers' => SelectCustomerResource::collection(Customer::with('addresses')->select(['id', 'first_name', 'last_name', 'phone'])->isNotBlackedList()->get()),
            'order' => null,
            'delivery_methods' => [
                [
                    'id' => 1,
                    'value' => 'ARMX'
                ],
                [
                    'id' => 2,
                    'value' => 'AZZAM DELIVERY'
                ],
                [
                    'id' => 3,
                    'value' => 'Mohsseeeeeeeen'
                ],
                [
                    'id' => 5,
                    'value' => 'Take Me'
                ],
            ],
            'payment_methods' => [
                [
                    'id' => 1,
                    'value' => 'Cache On Delivery'
                ],
                [
                    'id' => 2,
                    'value' => 'Visa Card'
                ],
                [
                    'id' => 3,
                    'value' => 'Master Card'
                ],
                [
                    'id' => 5,
                    'value' => 'OMT Card'
                ],
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreOrderRequest $request)
    {
        $productIds = (collect($request->selected_products)->pluck('id'));
        $allProducts = Product::with(['tax', 'pricesList'])->findMany($productIds);
        $defaultPricingClass = Setting::where('title', 'default_pricing_class')->first()->value;
        $allTaxes = Tax::all();
        $allTaxComponents = TaxComponent::all();

        DB::beginTransaction();
        try {
            $order = new Order();
            $order->customer_id = $request->client_id;
            $order->currency_id = $request->currency_id;
            $order->time = now();
            $order->date = now();


            $order->shipping_first_name = $request->shipping['first_name'];
            $order->shipping_last_name = $request->shipping['last_name'];
            $order->shipping_address_one = $request->shipping['address_1'];
            $order->shipping_address_two = $request->shipping['address_2'];
            $order->shipping_country_id = $request->shipping['country_id'];
            $order->shipping_city = $request->shipping['city'];
            $order->shipping_company_name = $request->shipping['company_name'];
            $order->shipping_email = $request->shipping['email_address'];
            $order->shipping_phone_number = $request->shipping['phone_number'];


            $order->billing_first_name = $request->billing['first_name'];
            $order->billing_last_name = $request->billing['last_name'];
            $order->billing_address_one = $request->billing['address_1'];
            $order->billing_address_two = $request->billing['address_2'];
            $order->billing_country_id = $request->billing['country_id'];
            $order->billing_city = $request->billing['city'];
            $order->billing_company_name = $request->billing['company_name'];
            $order->billing_email = $request->billing['email_address'];
            $order->billing_phone_number = $request->billing['phone_number'];

            $order->shipping_address_id = $request->shipping_address_id;
            $order->billing_address_id = $request->billing_address_id;


            if ($request->shipping_address_id == $request->billing_address_id || $request->is_billing_as_shipping) {
                $newAddress = null;
                if ($request->billing['edit_type'] == 'create') {
                    $newAddress = CustomerAddress::query()->create([
                        'customer_id' => $request->client_id,
                        'phone_number' => $request->billing['phone_number'],
                        'email_address' => $request->billing['email_address'],
                        'address_2' => $request->billing['address_2'],
                        'address_1' => $request->billing['address_1'],
                        'company_name' => $request->billing['company_name'],
                        'last_name' => $request->billing['last_name'],
                        'first_name' => $request->billing['first_name'],
                        'country_id' => $request->billing['country_id'],
                        'city' => $request->billing['city'],
                        'postal_code' => '',
                        'payment_method_id' => $request->billing['payment_method_id'],

                    ]);
                } elseif ($request->billing['edit_type'] == 'update' && !is_null($request->billing_address_id)) {
                    $newAddress = CustomerAddress::query()->findOrFail($request->billing_address_id)->update([
                        'customer_id' => $request->client_id,
                        'phone_number' => $request->billing['phone_number'],
                        'email_address' => $request->billing['email_address'],
                        'address_2' => $request->billing['address_2'],
                        'address_1' => $request->billing['address_1'],
                        'company_name' => $request->billing['company_name'],
                        'last_name' => $request->billing['last_name'],
                        'first_name' => $request->billing['first_name'],
                        'country_id' => $request->billing['country_id'],
                        'city' => $request->billing['city'],
                        'postal_code' => '',
                        'payment_method_id' => $request->billing['payment_method_id'],
                    ]);
                }
                $order->shipping_address_id = $request->billing_address_id;
                $order->billing_address_id = $request->billing_address_id;

                $request->is_billing_as_shipping = 1;
                $order->is_billing_as_shipping = 1;

                $order->shipping_first_name = $request->billing['first_name'];
                $order->shipping_last_name = $request->billing['last_name'];
                $order->shipping_address_one = $request->billing['address_1'];
                $order->shipping_address_two = $request->billing['address_2'];
                $order->shipping_country_id = $request->billing['country_id'];
                $order->shipping_city = $request->billing['city'];
                $order->shipping_company_name = $request->billing['company_name'];
                $order->shipping_email = $request->billing['email_address'];
                $order->shipping_phone_number = $request->billing['phone_number'];
            } else {

                if ($request->billing['edit_type'] == 'create') {
                    $newAddress = CustomerAddress::query()->create([
                        'customer_id' => $request->client_id,
                        'phone_number' => $request->billing['phone_number'],
                        'email_address' => $request->billing['email_address'],
                        'address_2' => $request->billing['address_2'],
                        'address_1' => $request->billing['address_1'],
                        'company_name' => $request->billing['company_name'],
                        'last_name' => $request->billing['last_name'],
                        'first_name' => $request->billing['first_name'],
                        'country_id' => $request->billing['country_id'],
                        'city' => $request->billing['city'],
                        'postal_code' => '',
                        'payment_method_id' => $request->billing['payment_method_id'],
                    ]);
                    $order->billing_address_id = $newAddress->id;
                } elseif ($request->billing['edit_type'] == 'update' && !is_null($request->billing_address_id)) {
                    CustomerAddress::query()->findOrFail($request->billing_address_id)->update([
                        'customer_id' => $request->client_id,
                        'phone_number' => $request->billing['phone_number'],
                        'email_address' => $request->billing['email_address'],
                        'address_2' => $request->billing['address_2'],
                        'address_1' => $request->billing['address_1'],
                        'company_name' => $request->billing['company_name'],
                        'last_name' => $request->billing['last_name'],
                        'first_name' => $request->billing['first_name'],
                        'country_id' => $request->billing['country_id'],
                        'city' => $request->billing['city'],
                        'postal_code' => '',
                        'payment_method_id' => $request->billing['payment_method_id'],
                    ]);

                    $order->billing_address_id = $request->billing_address_id;
                }

                if ($request->shipping['edit_type'] == 'create') {
                    $newAddress = CustomerAddress::query()->create([
                        'customer_id' => $request->client_id,
                        'phone_number' => $request->shipping['phone_number'],
                        'email_address' => $request->shipping['email_address'],
                        'address_2' => $request->shipping['address_2'],
                        'address_1' => $request->shipping['address_1'],
                        'company_name' => $request->shipping['company_name'],
                        'last_name' => $request->shipping['last_name'],
                        'first_name' => $request->shipping['first_name'],
                        'country_id' => $request->shipping['country_id'],
                        'city' => $request->shipping['city'],
                        'postal_code' => '',

                    ]);
                    $order->shipping_address_id = $newAddress->id;
                } elseif ($request->shipping['edit_type'] == 'update' && !is_null($request->shipping_address_id)) {
                    CustomerAddress::query()->findOrFail($request->shipping_address_id)->update([
                        'customer_id' => $request->client_id,
                        'phone_number' => $request->shipping['phone_number'],
                        'email_address' => $request->shipping['email_address'],
                        'address_2' => $request->shipping['address_2'],
                        'address_1' => $request->shipping['address_1'],
                        'company_name' => $request->shipping['company_name'],
                        'last_name' => $request->shipping['last_name'],
                        'first_name' => $request->shipping['first_name'],
                        'country_id' => $request->shipping['country_id'],
                        'city' => $request->shipping['city'],
                        'postal_code' => '',

                    ]);
                    $order->shipping_address_id = $request->billing_address_id;
                }
            }

            $order->customer_comment = $request->comment;
            $order->order_status_id = $request->status_id;
            $defaultCurrency = Currency::where('is_default', 1)->first();
            $selectedCurrency = Currency::query()->find($request->currency_id);
            if (is_null($defaultCurrency)) {
                return $this->errorResponse('There is no default currency!');
            }

            $order->currency_rate = $request->currency_rate;

            if ($request->currency_id == $defaultCurrency->id) {
                $order->currency_rate = 1;
            }

            $order->is_billing_as_shipping = $request->is_billing_as_shipping;

            $coupon = Coupon::where('code', $request->coupon_code)->first();

            $order->coupon_id =  $coupon ? $coupon->id : null;
            $products = $request->selected_products;

            $order->prefix = uniqid('order-');

            $order->billing_first_name = $request->billing['first_name'];
            $order->billing_last_name = $request->billing['last_name'];
            $order->billing_company_name = $request->billing['company_name'];
            $order->billing_address_one = $request->billing['address_1'];
            $order->billing_address_two = $request->billing['address_2'];
            $order->billing_city = $request->billing['city'];
            $order->billing_country_id = $request->billing['country_id'];
            $order->billing_email = $request->billing['email_address'];
            $order->billing_phone_number = $request->billing['phone_number'];
            $order->payment_method_id = $request->billing['payment_method_id'];
            $order->save();

            OrdersService::createNotesForOrder(order: $order, notes: $request->notes ?? [], data: $request->toArray());

            $productsOrders = OrdersService::calculateTotalOrderPrice($products, $order);

            $differencePrice = abs(($order->total) - $request->total_price);
            if ($differencePrice >= 0.001) {
                return $this->errorResponse('Sorry but there was a problem with the calculations! ', [
                    'shipping' => 12,
                    'order_total' => $order->total
                ]);
            }

            $order->save();

            $order->selected_products = OrdersService::generateOrderProducts($productsOrders, $defaultPricingClass, $allTaxComponents, $allTaxes, $selectedCurrency);
            OrdersService::adjustQuantityOfOrderProducts($order->selected_products, $allProducts);

            DB::commit();
            return $this->successResponse('The order has been created successfully !', [
                'order' => new SingelOrdersResource($order->load(['status', 'coupon', 'products', 'notes']))
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse('The Order has not been created successfully!' . 'error message: ' . $exception);
        } catch (\Error $error) {
            DB::rollBack();
            return $this->errorResponse('The Order has not been created successfully!' . 'error message: ' . $error);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Order Order
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Order $order)
    {
        $selectedCurrency = Currency::query()->find($order->currency_id);
        $orderProducts =  OrderProduct::where('order_id', $order->id)->get();
        $allProducts = Product::with(['tax', 'pricesList'])->get();
        $defaultPricingClass = Setting::where('title', 'default_pricing_class')->first()->value;
        $allTaxes = Tax::all();
        $defaultCurrency = Currency::where('is_default', 1)->first();
        $allTaxComponents = TaxComponent::all();

        $order->selected_products =  OrdersService::generateOrderProducts($orderProducts, $defaultPricingClass, $allTaxComponents, $allTaxes, $selectedCurrency);

        return $this->successResponse(data: [
            'order' => new SingelOrdersResource($order->load(['status', 'coupon', 'notes']))
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
     * @param  Order  $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreOrderRequest $request, Order $order)
    {
        $selectedCurrency = Currency::query()->find($request->currency_id);
        $productIds = (collect($request->selected_products)->pluck('id'));
        $allProducts = Product::with(['tax', 'pricesList'])->findMany($productIds);
        $oldProducts = $order->products;
        $oldOrderProducts = OrderProduct::query()->where('order_id', $order->id)->get();
        $allOrdersWithProducts = OrderProduct::all()->toArray();
        try {
            $defaultPricingClass = Setting::where('title', 'default_pricing_class')->first()->value;
            $allTaxes = Tax::all();
            $allTaxComponents = TaxComponent::all();
            $orderProducts =  OrderProduct::where('order_id', $order->id)->get();
            $defaultCurrency = Currency::where('is_default', 1)->first();

            $order->currency_rate = $request->currency_rate;

            if ($request->currency_id == $defaultCurrency->id) {
                $order->currency_rate = 1;
            }
            $order->customer_id = $request->client_id;
            $order->currency_id = $request->currency_id;

            $order->shipping_first_name = $request->shipping['first_name'];
            $order->shipping_last_name = $request->shipping['last_name'];
            $order->shipping_address_one = $request->shipping['address_1'];
            $order->shipping_address_two = $request->shipping['address_2'];
            $order->shipping_country_id = $request->shipping['country_id'];
            $order->shipping_city = $request->shipping['city'];
            $order->shipping_company_name = $request->shipping['company_name'];
            $order->shipping_email = $request->shipping['email_address'];
            $order->shipping_phone_number = $request->shipping['phone_number'];


            $order->billing_first_name = $request->billing['first_name'];
            $order->billing_last_name = $request->billing['last_name'];
            $order->billing_address_one = $request->billing['address_1'];
            $order->billing_address_two = $request->billing['address_2'];
            $order->billing_country_id = $request->billing['country_id'];
            $order->billing_city = $request->billing['city'];
            $order->billing_company_name = $request->billing['company_name'];
            $order->billing_email = $request->billing['email_address'];
            $order->billing_phone_number = $request->billing['phone_number'];

            $order->shipping_address_id = $request->shipping_address_id;
            $order->billing_address_id = $request->billing_address_id;

            if ($request->shipping_address_id == $request->billing_address_id || $request->is_billing_as_shipping) {
                $newAddress = null;
                if ($request->billing['edit_type'] == 'create') {
                    $newAddress = CustomerAddress::query()->create([
                        'customer_id' => $request->client_id,
                        'phone_number' => $request->billing['phone_number'],
                        'email_address' => $request->billing['email_address'],
                        'address_2' => $request->billing['address_2'],
                        'address_1' => $request->billing['address_1'],
                        'company_name' => $request->billing['company_name'],
                        'last_name' => $request->billing['last_name'],
                        'first_name' => $request->billing['first_name'],
                        'country_id' => $request->billing['country_id'],
                        'city' => $request->billing['city'],
                        'postal_code' => '',
                        'payment_method_id' => $request->billing['payment_method_id'],

                    ]);
                } elseif ($request->billing['edit_type'] == 'update' && !is_null($request->billing_address_id)) {
                    $newAddress = CustomerAddress::query()->findOrFail($request->billing_address_id)->update([
                        'customer_id' => $request->client_id,
                        'phone_number' => $request->billing['phone_number'],
                        'email_address' => $request->billing['email_address'],
                        'address_2' => $request->billing['address_2'],
                        'address_1' => $request->billing['address_1'],
                        'company_name' => $request->billing['company_name'],
                        'last_name' => $request->billing['last_name'],
                        'first_name' => $request->billing['first_name'],
                        'country_id' => $request->billing['country_id'],
                        'city' => $request->billing['city'],
                        'postal_code' => '',
                        'payment_method_id' => $request->billing['payment_method_id'],
                    ]);
                }
                $order->shipping_address_id = $request->billing_address_id;
                $order->billing_address_id = $request->billing_address_id;

                $request->is_billing_as_shipping = 1;
                $order->is_billing_as_shipping = 1;


                $order->shipping_first_name = $request->billing['first_name'];
                $order->shipping_last_name = $request->billing['last_name'];
                $order->shipping_address_one = $request->billing['address_1'];
                $order->shipping_address_two = $request->billing['address_2'];
                $order->shipping_country_id = $request->billing['country_id'];
                $order->shipping_city = $request->billing['city'];
                $order->shipping_company_name = $request->billing['company_name'];
                $order->shipping_email = $request->billing['email_address'];
                $order->shipping_phone_number = $request->billing['phone_number'];
            } else {

                if ($request->billing['edit_type'] == 'create') {
                    $newAddress = CustomerAddress::query()->create([
                        'customer_id' => $request->client_id,
                        'phone_number' => $request->billing['phone_number'],
                        'email_address' => $request->billing['email_address'],
                        'address_2' => $request->billing['address_2'],
                        'address_1' => $request->billing['address_1'],
                        'company_name' => $request->billing['company_name'],
                        'last_name' => $request->billing['last_name'],
                        'first_name' => $request->billing['first_name'],
                        'country_id' => $request->billing['country_id'],
                        'city' => $request->billing['city'],
                        'postal_code' => '',
                        'payment_method_id' => $request->billing['payment_method_id'],
                    ]);
                    $order->billing_address_id = $newAddress->id;
                } elseif ($request->billing['edit_type'] == 'update' && !is_null($request->billing_address_id)) {
                    CustomerAddress::query()->findOrFail($request->billing_address_id)->update([
                        'customer_id' => $request->client_id,
                        'phone_number' => $request->billing['phone_number'],
                        'email_address' => $request->billing['email_address'],
                        'address_2' => $request->billing['address_2'],
                        'address_1' => $request->billing['address_1'],
                        'company_name' => $request->billing['company_name'],
                        'last_name' => $request->billing['last_name'],
                        'first_name' => $request->billing['first_name'],
                        'country_id' => $request->billing['country_id'],
                        'city' => $request->billing['city'],
                        'postal_code' => '',
                        'payment_method_id' => $request->billing['payment_method_id'],
                    ]);

                    $order->billing_address_id = $request->billing_address_id;
                }

                if ($request->shipping['edit_type'] == 'create') {
                    $newAddress = CustomerAddress::query()->create([
                        'customer_id' => $request->client_id,
                        'phone_number' => $request->shipping['phone_number'],
                        'email_address' => $request->shipping['email_address'],
                        'address_2' => $request->shipping['address_2'],
                        'address_1' => $request->shipping['address_1'],
                        'company_name' => $request->shipping['company_name'],
                        'last_name' => $request->shipping['last_name'],
                        'first_name' => $request->shipping['first_name'],
                        'country_id' => $request->shipping['country_id'],
                        'city' => $request->shipping['city'],
                        'postal_code' => '',

                    ]);
                    $order->shipping_address_id = $newAddress->id;
                } elseif ($request->shipping['edit_type'] == 'update' && !is_null($request->shipping_address_id)) {
                    CustomerAddress::query()->findOrFail($request->shipping_address_id)->update([
                        'customer_id' => $request->client_id,
                        'phone_number' => $request->shipping['phone_number'],
                        'email_address' => $request->shipping['email_address'],
                        'address_2' => $request->shipping['address_2'],
                        'address_1' => $request->shipping['address_1'],
                        'company_name' => $request->shipping['company_name'],
                        'last_name' => $request->shipping['last_name'],
                        'first_name' => $request->shipping['first_name'],
                        'country_id' => $request->shipping['country_id'],
                        'city' => $request->shipping['city'],
                        'postal_code' => '',

                    ]);
                    $order->shipping_address_id = $request->billing_address_id;
                }
            }


            $order->time = now();
            $order->date = now();
            $order->customer_comment = $request->comment;
            $order->order_status_id = $request->status_id;

            $order->shipping_address_id = $request->shipping_address_id;
            $order->billing_address_id = $request->billing_address_id;


            if ($request->shipping_address_id == $request->billing_address_id) {
                $request->is_billing_as_shipping = 1;
                $order->is_billing_as_shipping = 1;
            }

            if (is_null($defaultCurrency)) {
                return $this->errorResponse('There is no default currency!');
            }

            $coupon = Coupon::where('code', $request->coupon_code)->first();

            $order->coupon_id =  $coupon ? $coupon->id : null;
            $products = $request->selected_products;
            //            $order->is_billing_as_shipping = $request->is_billing_as_shipping;

            $order->prefix = uniqid('order-');


            $order->billing_first_name = $request->billing['first_name'];
            $order->billing_last_name = $request->billing['last_name'];
            $order->billing_company_name = $request->billing['company_name'];
            $order->billing_address_one = $request->billing['address_1'];
            $order->billing_address_two = $request->billing['address_2'];
            $order->billing_city = $request->billing['city'];
            $order->billing_country_id = $request->billing['country_id'];
            $order->billing_email = $request->billing['email_address'];
            $order->billing_phone_number = $request->billing['phone_number'];
            $order->payment_method_id = $request->billing['payment_method_id'];

            OrdersService::updateProductsOfOrder($order, $request->selected_products, $oldOrderProducts->toArray(), $allOrdersWithProducts);

            $order->save();

            OrdersService::updateNotesForOrder($order, $request->notes ?? [], $request->toArray());

            $productsOrders = OrdersService::calculateTotalOrderPrice($products, $order, 'update');
            $differencePrice = abs(($order->total) - $request->total_price);
            if ($differencePrice >= 0.001) {
                return $this->errorResponse('Sorry but there was a problem with the calculations! ');
            }

            $order->save();

            $order->selected_products = OrdersService::generateOrderProducts($productsOrders, $defaultPricingClass, $allTaxComponents, $allTaxes, $selectedCurrency);
            OrdersService::adjustQuantityOfOrderProducts($order->selected_products, $allProducts);
            DB::commit();
            return $this->successResponse('The order has been created successfully !', [
                'order' => new SingelOrdersResource($order->load(['status', 'coupon', 'products', 'notes']))
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse('The Order has not been created successfully!' . 'error message: ' . $exception);
        } catch (\Error $error) {
            DB::rollBack();
            return $this->errorResponse('The Order has not been created successfully!' . 'error message: ' . $error);
        }
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

    public function getTableHeaders()
    {
        return $this->successResponse('Success!', ['headers' => __('headers.orders')]);
    }

    public function getOrdersData()
    {
        return $this->successResponsePaginated(RestFullOrderResource::class, Order::class, ['customer', 'coupon', 'status', 'shippingCountry', 'billingCountry', 'paymentMethod', 'products', 'notes']);
    }
}
