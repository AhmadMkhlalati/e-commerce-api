<?php

namespace App\Models\Language;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MainModel;
use Spatie\Translatable\HasTranslations;

class Language extends MainModel
{
    use HasFactory, HasTranslations;
    protected array $translatable = ['name'];
    protected $guard_name = 'web';
    public static $imagesPath = [
        'images' => 'languages/images',
    ];
}
