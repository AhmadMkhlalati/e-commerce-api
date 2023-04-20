<?php

namespace App\Models\Tax;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MainModel;
use App\Models\Tax\Tax;
use Spatie\Translatable\HasTranslations;

class TaxComponent extends MainModel
{
    use HasFactory,HasTranslations;
    protected $translatable=[''];
    protected $table='taxes_components';
    protected $guard_name = 'web';

    public function tax(){
        return $this->belongsTo(Tax::class,'tax_id');
    }
    public function taxChilds(){
        return $this->belongsTo(Tax::class,'component_tax_id');
    }
}
