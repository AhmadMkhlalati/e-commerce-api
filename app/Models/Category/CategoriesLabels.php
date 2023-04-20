<?php

namespace App\Models\Category;

use App\Models\MainModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class CategoriesLabels extends MainModel
{
    use HasFactory,HasTranslations;
    protected $translatable=[''];
    protected $table='categories_labels';
    protected $fillable = ['label_id','category_id'];

}
