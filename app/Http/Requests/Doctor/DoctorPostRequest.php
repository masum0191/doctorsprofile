<?php
namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class DoctorPostRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        return [
            'title'         => 'required|string|max:255',
            'slug'          => 'nullable|string|max:255',
            'category_id'   => 'nullable|string|max:100',
            'type_id'       => 'nullable|string|max:100',
            'cover_image'   => 'nullable|string', // store path or full URL
            'excerpt'       => 'nullable|string|max:1000',
            'body'          => 'nullable|string',
            'read_minutes'  => 'nullable|integer|min:1|max:120',
            'is_published'  => 'boolean',
            'published_at'  => 'nullable|date',
            'meta_title'        => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string|max:500',
            'meta_keywords'     => 'nullable|array',
            'meta_keywords.*'   => 'nullable|string|max:500',
            'order_column'      => 'nullable|integer|min:0',
            'related_post_ids'   => 'nullable|array',
            'related_post_ids.*' => 'nullable|integer|exists:doctor_posts,id',
        ];
    }
}
