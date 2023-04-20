<?php

namespace App\Http\Resources\Setting;

use App\Models\Price\Price;
use App\Models\Settings\Setting;
use App\Services\Setting\SettingService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;

class SettingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     * @throws \Throwable
     */
    public function toArray($request)
    {
        $idsArray = [];
        $titlesArray = [];
        $typesArray = [];
        $valuesArray = [];

        collect(getSettings())->map(function ($setting) use (&$idsArray, &$titlesArray, &$typesArray, &$valuesArray) {
            $idsArray[] = $setting->id;
            $titlesArray[] = $setting->title;
            $typesArray[] = $setting->type;
            $valuesArray[] = $setting->value;
        });

        $options = [];

        if (in_array($this->title, Setting::$fields) && ($this->type == 'select' || $this->type == 'multi-select' || $this->type == 'model_select')) {
            $options = Setting::getTitleOptions()[$this->title];
        }
        if ($this->title == 'default_pricing_class') {
            foreach (Setting::getTitleOptions()['default_pricing_class'] as $key => $option)
                $options[$key]['name'] = $option['name']['en'];
        }
        $id = $idsArray[array_search($this->title, $titlesArray)];
        $title = $titlesArray[array_search($this->title, $titlesArray)];
        $type = $typesArray[array_search($this->title, $titlesArray)];

        $value = $valuesArray[array_search($this->title, $titlesArray)];

        $value = match ($type) {
            'number' => (int)$value ?? 0,
            'checkbox' => (bool)$value ?? false,
            'multi-select' => $value ?? [],
            'model-select' => (int)$value ?? null,
            default => $value ??  null,
        };

        if ($type == 'model-select')
            $type = 'select';
        return [
            'key' => $id,
            'title' => $title,
            'name' => ucwords(str_replace("_", " ", $title)),
            'type' => $type,
            'options' => ($options),
            'value' => $value
        ];
    }
}
