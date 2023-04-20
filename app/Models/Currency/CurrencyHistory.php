<?php



namespace App\Models\Currency;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MainModel;
use App\Models\Currency\Currency;
use Spatie\Translatable\HasTranslations;

class CurrencyHistory extends MainModel
{
    use HasFactory,HasTranslations;
    protected $translatable=[''];
    protected $table='currencies_histories';
    protected $fillable=['currency_id','rate'];
    protected $guard_name = 'web';


    public function currency(){
        return $this->belongsTo(Currency::class,'currency_id');
    }
}
