<?php

namespace App\Models\Brand;

use App\Models\Field\Field;
use App\Models\MainModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class BrandField extends MainModel
{
    use HasFactory,HasTranslations;
    protected array $translatable=[];
    protected $table = 'brands_fields';
    protected $fillable = ['value','field_value_id','field_id','brand_id'];

    public function field(){
        return $this->hasOne(Field::class,'id','field_id');
    }


}
