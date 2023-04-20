<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    public function addresses(){
        return $this->hasMany(CustomerAddress::class,'customer_id','id');
    }

    public function scopeIsNotBlackedList($query){
        return $query->WhereNot('is_blacklist',1);
    }
}
