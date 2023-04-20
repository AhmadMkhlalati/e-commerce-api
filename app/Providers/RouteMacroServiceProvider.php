<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RouteMacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Route::macro('customBrandResource', function ($uri, $controller) {
            Route::get("$uri/order",[$controller,'getAllBrandsSorted']);
            Route::patch("$uri/toggle-status/{id}",[$controller,'toggleStatus']);
            Route::get("$uri/update-order",[$controller,'updateSortValues']);
            Route::post("$uri/all",[$controller,'index']);
            Route::get("$uri/create",[$controller,'create']);

            Route::apiResource($uri, $controller);
        });

        Route::macro('customCategoryResource', function ($uri, $controller) {
            Route::get("$uri/parents-order",[$controller,'getAllParentsSorted']);
            Route::get("$uri/children-order/{parent_id}",[$controller,'getAllChildsSorted']);
            Route::patch("$uri/toggle-status/{id}",[$controller,'toggleStatus']);
            Route::get("$uri/update-order",[$controller,'updateSortValues']);
            Route::post("$uri/all",[$controller,'index']);
            Route::get("$uri/create",[$controller,'create']);

            Route::apiResource($uri, $controller);
        });


        Route::macro('customLanguageResource', function ($uri, $controller) {
            Route::get("$uri/order",[$controller,'getAllLanguagesSorted']);
            Route::patch("$uri/toggle-status/{id}",[$controller,'toggleStatus']);
            Route::get("$uri/update-order",[$controller,'updateSortValues']);
            Route::put("$uri/change-language/{lang}",[$controller,'setLanguage']);     //change language for dashboard and get the dashborad translated objects
            Route::patch("$uri/set-is-default/{id}",[$controller,'setLanguageIsDefault']);
            Route::post("$uri/all",[$controller,'index']);

            Route::apiResource($uri, $controller);
        });

    }
}
