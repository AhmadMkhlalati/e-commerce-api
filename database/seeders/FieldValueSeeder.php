<?php

namespace Database\Seeders;

use App\Models\Field\FieldValue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FieldValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FieldValue::query()->truncate();

        FieldValue::query()->insert([
            [
            'field_id' => 1,
            'value' => json_encode(['en' => 'true','ar' => 'خطأ']),

        ],

        [
            'field_id' => 2,
            'value' => json_encode(['en' => 'black','ar' => 'أسود']),

        ],

        [
            'field_id' => 3,
            'value' => json_encode(['en' => 'XL','ar' => 'اكس لارج']),

        ],
    ]);
    }
}
