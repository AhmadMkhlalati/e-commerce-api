<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\Brand\BrandController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Country\CountryController;
use App\Http\Controllers\Coupons\CouponsController;
use App\Http\Controllers\Currency\CurrencyController;
use App\Http\Controllers\Discount\DiscountController;
use App\Http\Controllers\Discount\DiscountEntityController;
use App\Http\Controllers\Fields\FieldsController;
use App\Http\Controllers\Fields\FieldValueController;
use App\Http\Controllers\Label\LabelController;
use App\Http\Controllers\Language\LanguageController;
use App\Http\Controllers\Orders\OrdersController;
use App\Http\Controllers\Prices\PricesController;
use App\Http\Controllers\Prices\PricesListController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\RolesAndPermissions\RolesController;
use App\Http\Controllers\Settings\SettingsController;
use App\Http\Controllers\Tag\TagController;
use App\Http\Controllers\Tax\TaxController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Unit\UnitController;
use App\Http\Controllers\Users\CustomersController;
use App\Http\Controllers\Users\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
$dashboardMiddlewares = ['auth:sanctum', 'localization', 'role_permissions'];

//if (env('APP_DEBUG')) {
//    $key = array_search('auth:sanctum', $dashboardMiddlewares);
//    unset($dashboardMiddlewares[$key]);
//
//    $key = array_search('role_permissions', $dashboardMiddlewares);
//    unset($dashboardMiddlewares[$key]);
//}
Route::get('test', [TestController::class, 'test']);
Route::get('createShipment', [TestController::class, 'createShipment']);


Route::post('login', [AuthenticationController::class, 'login'])->name('login');
Route::get('logout', [AuthenticationController::class, 'logout'])->name('logout');

Route::group(['prefix' => 'dashboard', 'middleware' => $dashboardMiddlewares], function () {
    Route::get('/profile', fn() => auth()->user());
    Route::group(['prefix' => 'headers'], function () {
        Route::get('brands', [BrandController::class, 'getTableHeaders']);
        Route::get('categories', [CategoryController::class, 'getTableHeaders']);
        Route::get('countries', [CountryController::class, 'getTableHeaders']);
        Route::get('currencies', [CurrencyController::class, 'getTableHeaders']);
        Route::get('discounts', [DiscountController::class, 'getTableHeaders']);
        Route::get('fields', [FieldsController::class, 'getTableHeaders']);
        Route::get('labels', [LabelController::class, 'getTableHeaders']);
        Route::get('languages', [LanguageController::class, 'getTableHeaders']);
        Route::get('prices', [PricesController::class, 'getTableHeaders']);
        Route::post('prices_list', [PricesListController::class, 'getTableHeaders']);
        Route::get('roles', [RolesController::class, 'getTableHeaders']);
        Route::get('settings', [SettingsController::class, 'getTableHeaders']);
        Route::get('tags', [TagController::class, 'getTableHeaders']);
        Route::get('taxes', [TaxController::class, 'getTableHeaders']);
        Route::get('units', [UnitController::class, 'getTableHeaders']);
        Route::get('users', [UsersController::class, 'getTableHeaders']);
        Route::get('settings', [SettingsController::class, 'getTableHeaders']);
        Route::get('products', [ProductController::class, 'getTableHeaders']);
        Route::get('products-select', [ProductController::class, 'getTableHeadersForSelect']);
        Route::get('orders', [OrdersController::class, 'getTableHeaders']);
        Route::get('coupons', [CouponsController::class, 'getTableHeaders']);
    });
    // Routes Macro
    Route::customBrandResource('brand', BrandController::class);
    Route::customCategoryResource('category', CategoryController::class);
    Route::customLanguageResource('language', LanguageController::class);
    //End of Routes Macro

    Route::post('user/all', [UsersController::class, 'index']);//for searching
    Route::get('user/customer/get-addresses-of-customer/{customer}', [CustomersController::class, 'getCountriesOfCustomer']);
    Route::apiResource('user', UsersController::class);

    Route::apiResource('country', CountryController::class);
    Route::post('country/all', [CountryController::class, 'index']); // for search
//    Route::post("country/{country}",[CountryController::class,'update']);

    Route::post('order/all', [OrdersController::class, 'index']);// for search
    Route::get('order/create', [OrdersController::class, 'create']);
    Route::apiResource('order', OrdersController::class);

    Route::apiResource('currency', CurrencyController::class);
    Route::patch('currency/set-is-default/{id}', [CurrencyController::class, 'setCurrencyIsDefault']);
    Route::post('currency/all', [CurrencyController::class, 'index']); // for search

    Route::apiResource('discount', DiscountController::class);
    Route::post('discount/all', [DiscountController::class, 'index']); // for search

    Route::apiResource('category', CategoryController::class);

    Route::apiResource('brands', BrandController::class);

    Route::apiResource('discount-entity', DiscountEntityController::class);
    Route::post('discount-entity/all', [DiscountEntityController::class, 'index']); // for search

    Route::apiResource('field', FieldsController::class);
    Route::post('field/all', [FieldsController::class, 'index']);// for search

    Route::apiResource('field-value', FieldValueController::class);
    Route::post('field-value/all', [FieldValueController::class, 'index']);// for search

    Route::apiResource('label', LabelController::class);
    Route::post('label/all', [LabelController::class, 'index']);// for search

    Route::post('role/all', [RolesController::class, 'index']);// for search
    Route::post('role/get-nested-permissions-for-role/', [RolesController::class, 'getNestedPermissionsForRole']);
    Route::get('role/get-all-roles', [RolesController::class, 'getAllRoles']); //for select box
    Route::apiResource('role', RolesController::class);

    Route::apiResource('setting', SettingsController::class);
    Route::post('setting/all', [SettingsController::class, 'index']);// for search

    Route::post('tag/all', [TagController::class, 'index']);// for search
    Route::apiResource('tag', TagController::class);

    Route::apiResource('unit', UnitController::class);
    Route::post('unit/all', [UnitController::class, 'index']);// for search

    // @TODO: make a correct function for the user profile

    Route::post('tax/all', [TaxController::class, 'index']);//for searching
    Route::get('tax/create', [TaxController::class, 'create']);
    Route::apiResource('tax', TaxController::class);
    Route::post('tax/all', [TaxController::class, 'index']);// for search

    Route::get('price/get-list', [PricesController::class, 'getPricesList']);
    Route::post('price/all', [PricesController::class, 'index']);// for search
    Route::get('price/get-original-prices', [PricesController::class, 'getOriginalPrices'])->name('get.original.prices');
    Route::apiResource('price', PricesController::class);

    Route::post('price_list/show', [PricesListController::class, 'show']);
    Route::PUT('price_list', [PricesListController::class, 'update']);
    Route::apiResource('price_list', PricesListController::class);

    Route::post('product/all', [ProductController::class, 'index']);// for search
    // Route::post('product/add',[ProductController::class,'addproduct']);// for search
    Route::get('product/create', [ProductController::class, 'create']);

    Route::post('product/get-products-for-order', [ProductController::class, 'getProductsForOrders']);

    Route::post('product/bundle', [ProductController::class, 'getAllProductsAndPrices']);

    Route::apiResource('product', ProductController::class);

    Route::post('coupon/get-coupon-by-code/{code}', [CouponsController::class, 'getCouponByCode']);
    Route::post('coupon/all', [CouponsController::class, 'index']);
    Route::get('coupon/create', [CouponsController::class, 'create']);
    Route::apiResource('coupon', CouponsController::class);

    Route::get('createShipment', [TestController::class, 'createShipment']);

});
