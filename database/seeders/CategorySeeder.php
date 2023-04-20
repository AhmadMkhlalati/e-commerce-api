<?php

namespace Database\Seeders;

use App\Models\Category\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::query()->truncate();

                Category::query()->insert([[
                    'name' => json_encode(['en' => 'face','ar' => 'وجه']),
                    'code' => "face",
                    'slug' =>   "face",
                    'parent_id' =>  null,
                    'is_disabled' => 0,
                ],

                [
                    'name' =>  json_encode(['en' => 'hair','ar' => 'شعر']),
                    'code' => "hair",
                    'slug' =>   "hair",
                    'parent_id' =>  null,
                    'is_disabled' => 0,
                ],
                [
                    'name' => json_encode(['en' => 'cream','ar' => 'كريم']),
                    'code' => "cream",
                    'slug' =>   "cream",
                    'parent_id' =>  null,
                    'is_disabled' => 0,
                ],
                [
                    'name' => json_encode(['en' => 'white Skin','ar' => 'بشرة بيضاء']),
                    'code' => "Wskin",
                    'slug' =>   "Wskin",
                    'parent_id' =>  null,
                    'is_disabled' => 0,
                ],
                [
                    'name' => json_encode(['en' => 'black Skin','ar' => 'بشرة غامقة']),
                    'code' => "black skin",
                    'slug' =>   "black_skin",
                    'parent_id' =>  null,
                    'is_disabled' => 0,
                ],

            ]);




    }
}
