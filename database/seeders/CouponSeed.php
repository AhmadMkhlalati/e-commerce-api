<?php

namespace Database\Seeders;

use App\Models\Coupons\Coupon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CouponSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Coupon::query()->truncate();

        Coupon::query()->insert([
            [
                'title' => json_encode(['en' => 'Spring Discount', 'ar' => 'خصم الربيع']),
                'code' => 'spr100',
                'start_date'=> now(),
                'expiry_date' => null,
                'discount_percentage' => 50,
                'discount_amount' => null,
                'min_amount' => null,
                'is_one_time' => false,
                'is_used' => 0,
            ],
            [
                'title' => json_encode(['en' => 'Spring Discount', 'ar' => 'خصم الشتاء']),
                'code' => 'win100',
                'start_date'=> now(),
                'expiry_date' => now()->addMonths(5),
                'discount_percentage' => 60,
                'discount_amount' => null,
                'min_amount' => null,
                'is_one_time' => false,
                'is_used' => 0,
            ],
            [
                'title' =>json_encode(['en' => 'Lets Play', 'ar' => 'هيا نلعب']),
                'code' => 'play',
                'start_date'=> now(),
                'expiry_date' => null,
                'discount_percentage' => 10,
                'discount_amount' => null,
                'min_amount' => null,
                'is_one_time' => true,
                'is_used' => 0,
            ],
        ]);
    }
}
