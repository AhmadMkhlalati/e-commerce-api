<?php

namespace App\Http\Requests\Coupons;

use App\Http\Requests\MainRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CouponRequest extends MainRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $id = $this->coupon ? $this->coupon->id : null;
        return [
            'title' => 'required|',
            'code' => 'unique:coupons,code,'.$id,
            'start_date' => 'nullable|date|after_or_equal:'.now()->toDateString(),
            'expiry_date' => ['nullable','date',Rule::when($this->has('start_date'), ['after_or_equal:start_date'])],
            'type' => ['required',Rule::in(['percentage','amount'])],
            'min_amount' => ['nullable','numeric'],
            'value' => ['required','numeric', Rule::when($this->type == 'amount' && $this->has('min_amount'), ['lte:'.$this->min_amount])],
            'is_one_time' => 'nullable|boolean',
        ];
    }
}
