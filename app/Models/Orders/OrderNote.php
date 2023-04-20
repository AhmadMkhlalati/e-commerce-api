<?php

namespace App\Models\Orders;

use App\Models\MainModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class OrderNote extends MainModel
{
    use HasFactory,HasTranslations;
    protected $translatable = [];
    protected $table = 'orders_notes';


}
