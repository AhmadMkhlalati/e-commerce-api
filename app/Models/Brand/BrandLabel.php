<?php

namespace App\Models\Brand;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class BrandLabel extends Model
{
    use HasFactory,HasTranslations;
    protected $table = 'brands_labels';
    protected $fillable = ['brand_id','label_id'];

}
