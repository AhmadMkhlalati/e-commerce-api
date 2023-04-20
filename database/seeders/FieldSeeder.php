<?php

namespace Database\Seeders;

use App\Models\Field\Field;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Field::query()->truncate();

        Field::query()->insert([
            [
                'title' => json_encode(['en' => 'isExpired', 'ar' => 'منتهي الصلاحية']),
                'type' => "checkbox",
                'entity' => "category",
                'is_required' => 1,
            ],

            [
                'title' => json_encode(['en' => 'color', 'ar' => 'اللون']),
                'type' => "text",
                'entity' => "category",
                'is_required' => 0,
            ],

            [
                'title' => json_encode(['en' => 'size', 'ar' => 'المقاس']),
                'type' => "select",
                'entity' => "category",
                'is_required' => 1,
            ],
            [
                'title' => json_encode(['en' => 'isExpired', 'ar' => 'منتهي الصلاحية']),
                'type' => "checkbox",
                'entity' => "product",
                'is_required' => 1,
            ],

            [
                'title' => json_encode(['en' => 'color', 'ar' => 'اللون']),
                'type' => "text",
                'entity' => "product",
                'is_required' => 0,
            ],

            [
                'title' => json_encode(['en' => 'size', 'ar' => 'المقاس']),
                'type' => "select",
                'entity' => "product",
                'is_required' => 1,
            ],
            [
                'title' => json_encode(['en' => 'isExpired', 'ar' => 'منتهي الصلاحية']),
                'type' => "checkbox",
                'entity' => "brand",
                'is_required' => 1,
            ],

            [
                'title' => json_encode(['en' => 'color', 'ar' => 'اللون']),
                'type' => "text",
                'entity' => "brand",
                'is_required' => 0,
            ],

            [
                'title' => json_encode(['en' => 'size', 'ar' => 'المقاس']),
                'type' => "select",
                'entity' => "brand",
                'is_required' => 1,
            ],
        ]);
    }
}
