<?php

namespace App\Http\Resources\Currency;

use App\Models\Currency\Currency;
use Illuminate\Http\Resources\Json\JsonResource;

class SelectCurrencyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $defaultCurrency = Currency::where('is_default' , 1)->first();
        if($defaultCurrency->id == $this->id){
            $rate = 1;
        }else{
            $rate = $this->rate;
        }
        return [
            'id' => $this->id,
            'value'=>$this->code ?? 'NON' . ' - '.$this->symbol ?? 'NON',
            'rate'=> $rate,
            'code' => $this->code,
            'symbol'=>$this->symbol,
            'is_default'=> (boolean)$this->is_default,

        ];
    }
}
