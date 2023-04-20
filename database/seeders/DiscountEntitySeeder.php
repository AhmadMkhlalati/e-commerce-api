<?php

namespace Database\Seeders;

use App\Models\Discount\DiscountEntity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiscountEntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DiscountEntity::query()->truncate();

        DiscountEntity::query()->insert([
            [
            'discount_id' => 1,
            'category_id' => 1,
            'brand_id' => 1,
            'tag_id' => 1,
        ],

        [
            'discount_id' => 2,
            'category_id' => 2,
            'brand_id' => 2,
            'tag_id' => 2,
        ],

        [
            'discount_id' => 3,
            'category_id' => 3,
            'brand_id' => 3,
            'tag_id' => 3,
        ],
    ]);
    }
}
