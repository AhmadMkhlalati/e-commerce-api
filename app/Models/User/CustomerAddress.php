<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    use HasFactory;
    protected $table = 'customer_address';

    protected $fillable = [
        'customer_id',
        'phone_number',
        'email_address',
        'address_2',
        'address_1',
        'company_name' ,
        'last_name',
        'first_name',
        'country_id',
        'city',
        'postal_code',
        'payment_method_id'
    ];
}
