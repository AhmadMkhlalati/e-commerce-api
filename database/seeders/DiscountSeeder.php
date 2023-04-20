<?php

namespace Database\Seeders;

use App\Models\Discount\Discount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Discount::query()->truncate();

        Discount::query()->insert([
            [
            'name' => json_encode(['en' => 'Mother day','ar' => ' يوم الأم']),
            'start_date' => date_format(date_create('2022-10-01'), 'Y-m-d'),
            'discount_percentage' =>  10,
        ],

        [
            'name' => json_encode(['en' => 'New Year Day','ar' => 'رأس السنة الميلادية']),
            'start_date' => date_format(date_create('2023-01-01'), 'Y-m-d'),
            'discount_percentage' =>  20,
        ],

        [
            'name' => json_encode(['en' => 'Eid al-Fitr','ar' => 'عيد الفطر']),
            'start_date' => date_format(date_create('2021-10-01'), 'Y-m-d'),
            'discount_percentage' =>  30,
        ],
    ]);
    }
}
