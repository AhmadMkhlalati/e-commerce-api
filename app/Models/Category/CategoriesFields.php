<?php

namespace App\Models\Category;

use App\Http\Requests\MainRequest;
use App\Models\Field\Field;
use App\Models\MainModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class CategoriesFields extends MainModel
{
    use HasFactory,HasTranslations;
    protected array $translatable=[];
    protected $table='categories_fields';
    protected $fillable = ['category_id','field_id','field_value_id','value'];

    public function field(){
        return $this->hasOne(Field::class,'id','field_id');
    }

}
