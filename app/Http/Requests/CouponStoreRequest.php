<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CouponStoreRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'code' => ['required','string','max:50','unique:coupons,code'],
            'type' => ['required','in:fixed,percent'],
            'value' => ['required','numeric','min:0.01'],
            'min_amount' => ['nullable','numeric','min:0'],
            'max_discount' => ['nullable','numeric','min:0'],
            'usage_limit' => ['nullable','integer','min:1'],
            'usage_limit_per_user' => ['nullable','integer','min:1'],
            'is_active' => ['nullable','boolean'],
            'starts_at' => ['nullable','date'],
            'expires_at' => ['nullable','date','after_or_equal:starts_at'],
            'note' => ['nullable','string','max:1000'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('code')) {
            $this->merge(['code' => strtoupper(trim($this->code))]);
        }
        $this->merge(['is_active' => $this->boolean('is_active')]);
    }
}
