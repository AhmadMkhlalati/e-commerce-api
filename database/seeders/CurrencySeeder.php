<?php

namespace Database\Seeders;

use App\Models\Currency\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Currency::query()->truncate();

        Currency::query()->insert([
            [
            'name' => json_encode(['en' => 'Dollar','ar' => ' دولار']),
            'code' => "USD",
            'rate' =>  30000,
        ],

        [
            'name' => json_encode(['en' =>'Lebanon Lera','ar' =>  'ليرة لبنانية']),
            'code' => "USA",
            'rate' =>  20000,
        ],

        [
            'name' => json_encode(['en' =>'Euro','ar' =>  'يورو']),
            'code' => "euro",
            'rate' =>  10000,
        ],
    ]);
    }
}
