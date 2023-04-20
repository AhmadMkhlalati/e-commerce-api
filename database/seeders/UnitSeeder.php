<?php

namespace Database\Seeders;

use App\Models\Unit\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Unit::query()->truncate();

        Unit::query()->insert([
            [
            'name' => json_encode(['en' => 'kilogram','ar' => 'كيلوغرام']),
            'code' => 'kg',
        ],

        [
            'name' => json_encode(['en' => 'piece','ar' => 'قطعة']),
            'code' => 'pcs',

        ],

        [
            'name' => json_encode(['en' => 'bundle','ar' => 'حزمة']),
            'code' => 'bundle',

        ],
    ]);
    }
}
