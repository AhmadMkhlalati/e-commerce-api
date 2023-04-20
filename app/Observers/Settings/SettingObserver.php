<?php

namespace App\Observers\Settings;

use App\Models\Settings\Setting;
use Illuminate\Support\Facades\Cache;

class SettingObserver
{
    /**
     * Handle the Setting "created" event.
     *
     * @param  \App\Models\Settings\Setting  $setting
     * @return void
     */
    public function created(Setting $setting)
    {
        Cache::forget(Setting::$cacheKey);
    }

    /**
     * Handle the Setting "updated" event.
     *
     * @param  \App\Models\Settings\Setting  $setting
     * @return void
     */
    public function updated(Setting $setting)
    {
        Cache::forget(Setting::$cacheKey);

    }

    /**
     * Handle the Setting "deleted" event.
     *
     * @param  \App\Models\Settings\Setting  $setting
     * @return void
     */
    public function deleted(Setting $setting)
    {
        Cache::forget(Setting::$cacheKey);

    }

    /**
     * Handle the Setting "restored" event.
     *
     * @param  \App\Models\Settings\Setting  $setting
     * @return void
     */
    public function restored(Setting $setting)
    {

    }

    /**
     * Handle the Setting "force deleted" event.
     *
     * @param  \App\Models\Settings\Setting  $setting
     * @return void
     */
    public function forceDeleted(Setting $setting)
    {
        Cache::forget(Setting::$cacheKey);

    }
}
