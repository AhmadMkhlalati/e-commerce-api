<?php

namespace App\Providers;

use App\Models\Settings\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class SettingsServiceProvider extends ServiceProvider
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
        if(Schema::hasTable('settings')){
            Cache::rememberForever('settings', function () {
                $setting=Setting::all(['id','title','type','value']);
                return $setting;
            });
        }



    }
}
