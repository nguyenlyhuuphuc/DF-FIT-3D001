<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductCreateRequest extends FormRequest
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
        return [
            'name' =>'required|min:3|max:256',
            'price'=> 'required|min:1|max:999999999|int',
            'image' => 'image',
            'product_category_id' => 'required',
            'status' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên sản phẩm',
            'name.min' => 'Tên sản phẩm phải có độ dài từ 3',
            'name.max' => 'Tên sản phẩm phải có độ dài 255 ký tự',
            'price.required' => 'Vui lòng nhập giá sản phẩm',
            'price.min' => 'Giá sản phẩm phải có độ dài từ 1',
            'price.max' => 'Giá sản phẩm phải có độ dài 999999999',
            'image.required' => 'Vui lòng chọn ảnh sản phẩm',
            'status.required' => 'Vui lòng chọn trang thai sản phẩm',
        ];
    }
}
