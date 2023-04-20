<?php

namespace Database\Seeders;

use App\Models\User\Customer;
use App\Models\User\CustomerAddress;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomeresAndAddressesSeeds extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Customer::query()->truncate();

        Customer::query()->insert([
            [
                'first_name' => 'mohammad',
                'last_name' => 'azzam',
                'address_id' => null,
                'email' => 'mohammad@test.com',
                'phone' => 96176023035,
                'is_blacklist' => 0,
                'blacklist_reason' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'bilal',
                'last_name' => 'abo jamous',
                'address_id' => null,
                'email' => 'bilal@test.com',
                'phone' => 96170011262,
                'is_blacklist' => 0,
                'blacklist_reason' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'ali',
                'last_name' => 'mohsen',
                'address_id' => null,
                'email' => 'ali@test.com',
                'phone' => 96171201417,
                'is_blacklist' => 0,
                'blacklist_reason' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        CustomerAddress::query()->truncate();

        CustomerAddress::query()->insert([
           [
               'customer_id' => 1,
               'street' => 'abra',
               'city' => 'saida',
               'postal_code' => 1600,
               'first_name' => 'mohammad',
               'last_name' => 'azzam',
               'country_id' => 1,
               'company_name' => 'Consg',
               'address_1' => 'saida,abra,helalye',
               'address_2' => 'saida,abra,helalye',
               'email_address' => 'azzam@test.com',
               'phone_number' => 96176023035,
               'payment_method_id' =>1
           ],
            [
                'customer_id' => 3,
                'street' => 'hara',
                'city' => 'saida',
                'postal_code' => 1600,
                'first_name' => 'ali',
                'country_id' => 1,
                'last_name' => 'mohsen',
                'company_name' => 'Consg',
                'address_1' => 'hara,saida,jadet nabih beree',
                'address_2' => '',
                'email_address' => 'mohsen@test.com',
                'phone_number' => 96171201417,
                'payment_method_id' =>2
            ],
        ]);
    }
}
