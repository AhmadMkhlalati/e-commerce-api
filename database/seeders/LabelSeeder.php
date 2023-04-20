<?php

namespace Database\Seeders;

use App\Models\Label\Label;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Label::query()->truncate();

        Label::query()->insert([
            [
            'title' => json_encode(['en' => 'New','ar' => 'جديد']),
            'entity' => "category",
            'color' =>  "black",
            'key' =>  "new category",
        ],

        [
            'title' => json_encode(['en' => '50%','ar' => '50%']),
            'entity' => "category",
            'color' =>  "Blue",
            'key' =>  "sale category",
        ],

        [
            'title' => json_encode(['en' => 'Hot','ar' => 'ناري']),
            'entity' => "category",
            'color' =>  "Red",
            'key' =>  "hot category",
        ],
        [
            'title' => json_encode(['en' => 'New','ar' => 'جديد']),
            'entity' => "product",
            'color' =>  "black",
            'key' =>  "new product",
        ],

        [
            'title' => json_encode(['en' => '50%','ar' => '50%']),
            'entity' => "product",
            'color' =>  "Blue",
            'key' =>  "sale product",
        ],

        [
            'title' => json_encode(['en' => 'Hot','ar' => 'ناري']),
            'entity' => "product",
            'color' =>  "Red",
            'key' =>  "hot product",
        ],
        [
            'title' => json_encode(['en' => 'New','ar' => 'جديد']),
            'entity' => "brand",
            'color' =>  "black",
            'key' =>  "new brand",
        ],

        [
            'title' => json_encode(['en' => '50%','ar' => '50%']),
            'entity' => "brand",
            'color' =>  "Blue",
            'key' =>  "sale brand",
        ],

        [
            'title' => json_encode(['en' => 'Hot','ar' => 'ناري']),
            'entity' => "brand",
            'color' =>  "Red",
            'key' =>  "hot brand",
        ],


    ]);
    }
}
