<?php

namespace App\Models\Discount;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountEntity extends Model
{
    use HasFactory;
    protected $table='discounts_entities';
    protected $guard_name = 'web';


}
