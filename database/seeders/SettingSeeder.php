<?php

namespace Database\Seeders;

use App\Models\Settings\Setting;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::query()->truncate();

        Setting::query()->insert([
            [
                'title' => "products_required_fields",
                'type' => 'multi-select',
                'value' => null,
                'is_developer' => 1,
                'created_at' => Carbon::now()->toDateString(),
                'updated_at' => Carbon::now()->toDateString(),
            ],

            [
                'title' => "products_quantity_greater_than_or_equal",
                'type' => 'number',
                'value' => 0,
                'is_developer' => 1,
                'created_at' => Carbon::now()->toDateString(),
                'updated_at' => Carbon::now()->toDateString(),

            ],

            [
                'title' => "allow_negative_quantity",
                'type' => 'checkbox',
                'value' => 0,
                'is_developer' => 1,
                'created_at' => Carbon::now()->toDateString(),
                'updated_at' => Carbon::now()->toDateString(),

            ],
            [
                'title' => "products_prices_greater_than_or_equal",
                'type' => 'number',
                'value' => 0,
                'is_developer' => 1,
                'created_at' => Carbon::now()->toDateString(),
                'updated_at' => Carbon::now()->toDateString(),

            ],
            [
                'title' => "products_discounted_price_greater_than_or_equal",
                'type' => 'number',
                'value' => 0,
                'is_developer' => 1,
                'created_at' => Carbon::now()->toDateString(),
                'updated_at' => Carbon::now()->toDateString(),

            ],
            [
                'title' => "default_pricing_class",
                'type' => 'model-select',
                'value' => null,
                'is_developer' => 1,
                'created_at' => Carbon::now()->toDateString(),
                'updated_at' => Carbon::now()->toDateString(),

            ],
        ]);
    }
}
