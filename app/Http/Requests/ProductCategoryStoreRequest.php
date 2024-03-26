<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductCategoryStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return[
            'name' => 'required|min:3|max:256',
            'slug' => 'required|min:3|max:256',
            'status' => 'required|boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên danh mục',
            'name.min' => 'Tên danh mục phải có độ dài từ 3',
            'name.max' => 'Tên danh mục phải có độ dài 255 ký tự',
            'slug.required' => 'Vui lòng nhập tên :attribute',
            'slug.min' => 'Tên :attribute phải có độ dài từ 3',
            'slug.max' => 'Tên :attribute phải có độ dài 255 ký tự',
            'status.required' => 'Vui lòng chọn trạng thái'
        ];
    }
}
