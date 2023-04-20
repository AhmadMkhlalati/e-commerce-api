<?php

namespace App\Models\Orders;

use App\Models\Coupons\Coupon;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User\Customer;
class Order extends Model
{
    use HasFactory;

    public function status(){
        return $this->hasOne(OrderStatus::class,'id','order_status_id');
    }

    public function coupon(){
        return $this->hasOne(Coupon::class,'id','coupon_id');
    }

    public function products(){
        return $this->BelongsToMany(Product::class,'order_products','order_id','product_id');
    }
    public function customer(){
        return $this->belongsTo(Customer::class,'customer_id','id');
    }
    public function notes(){
        return $this->hasMany(OrderNote::class, 'order_id','id');
    }

    public function paymentMethod(){
        return $this->hasOne(PaymentMethod::class,'id','payment_method_id');
    }

    public function shippingCountry(){
        return $this->hasOne(Country::class,'id','shipping_country_id');
    }

    public function billingCountry(){
        return $this->hasOne(Country::class,'id','billing_country_id');
    }


}
