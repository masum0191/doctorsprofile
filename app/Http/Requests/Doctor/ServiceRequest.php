<?php
namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array {
        return [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon'        => 'nullable|string|max:255',    // e.g., ri-heart-pulse-fill
            'badge'       => 'nullable|string|max:100',
            'features'    => 'nullable|array',
            'features.*'  => 'nullable|string|max:255',
        ];
    }
}
