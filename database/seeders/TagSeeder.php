<?php

namespace Database\Seeders;

use App\Models\Tag\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tag::query()->truncate();

        Tag::query()->insert([
            [
            'name' => json_encode(['en' => 'White skin','ar' => 'بشرة بيضاء']),
        ],

        [
            'name' => json_encode(['en' => 'Ramdan karim','ar' => 'رمضان كريم']),
        ],

        [
            'name' => json_encode(['en' => 'Eid al-adha','ar' => 'عيد الأضحى']),
        ],
    ]);
    }
}
