<?php

namespace Database\Seeders;

use App\Models\Payments\PaymentTypes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentsTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentTypes::query()->truncate();

        PaymentTypes::query()->insert([
            [
                'name' => json_encode(['en' => 'visa card', 'ar' => 'فيزا']),
                'is_valid' => 1
            ],
            [
                'name' => json_encode(['en' => 'master card', 'ar' => 'ماستر كارد']),
                'is_valid' => 1
            ],
        ]);
    }
}
