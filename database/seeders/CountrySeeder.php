<?php

namespace Database\Seeders;

use App\Models\Country\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Country::query()->truncate();

        Country::query()->insert([
        [
            'name' => json_encode(['en' => 'United States','ar' => 'الولايات المتحدة']),
            'iso_code_1' => "USA",
            'iso_code_2' =>   "US",
            'phone_code' =>  "+1",
            'flag' =>  "test.png",
        ],

        [
            'name' => json_encode(['en' => 'Lebanon','ar' => 'لبنان']),
            'iso_code_1' => "LB",
            'iso_code_2' =>   "LBp",
            'phone_code' =>  "+961",
            'flag' =>  "test.png",
        ],
        [
            'name' => json_encode(['en' => 'United Kingdom','ar' => 'المملكة المتحدة']),
            'iso_code_1' => "UK",
            'iso_code_2' =>   "UK",
            'phone_code' =>  "+44",
            'flag' =>  "test.png",
        ],
        [
            'name' => json_encode(['en' => 'Egypt','ar' => 'مصر']),
            'iso_code_1' => "EG",
            'iso_code_2' =>   "EG",
            'phone_code' =>  "+20",
            'flag' =>  "test.png",
        ],
        [
            'name' => json_encode(['en' => 'France','ar' => 'فرنسا']),
            'iso_code_1' => "Fr",
            'iso_code_2' =>   "Fr",
            'phone_code' =>  "+33",
            'flag' =>  "test.png",
        ],

    ]);
    }
}
