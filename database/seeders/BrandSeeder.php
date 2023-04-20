<?php

namespace Database\Seeders;

use App\Models\Brand\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Brand::query()->truncate();

        Brand::query()->insert([[
            'name' => json_encode(['en' => 'samsung','ar' => 'سامسونج']),
            'code' => "SA",
            'is_disabled' => 0,
        ],

        [
            'name' => json_encode(['en' => 'LG','ar' => 'لج']),
            'code' => "LG",
            'is_disabled' => 0,
        ],
        [
            'name' => json_encode(['en' => 'iphone','ar' => 'أيفون']),
            'code' => "ios",
            'is_disabled' => 0,
        ],
        [
            'name' => json_encode(['en' => 'android','ar' => 'أندرويد']),
            'code' => "android",
            'is_disabled' => 0,
        ],
        [
            'name' => json_encode(['en' => 'huwaei','ar' => 'هواوي']),
            'code' => "huwaei",
            'is_disabled' => 0,
        ],

    ]);

    }
}
