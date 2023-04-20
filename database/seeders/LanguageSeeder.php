<?php

namespace Database\Seeders;

use App\Models\Language\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Language::query()->truncate();

        Language::query()->insert([
            [
            'name' => json_encode(['en' => 'English','ar' => 'إنجليزي']),
            'code' => "en",
            'is_default' =>  0,
            'is_disabled' => 0,
        ],

        [
            'name' => json_encode(['en' => 'arabic','ar' => 'عربي']),
            'code' => "ar",
            'is_default' =>  0,
            'is_disabled' => 0,
        ],


    ]);
    }
}
