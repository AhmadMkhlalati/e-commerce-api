<?php

namespace App\Services\Setting;

use App\Models\Settings\Setting;

class SettingService
{
    public static function getSetting()
    {
        $setting = Setting::all('title');
        if ($setting) {
            return $setting;
        }
        return null;
    }

}
