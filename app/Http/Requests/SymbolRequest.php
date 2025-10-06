<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SymbolRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // نفترض أن أي مستخدم مصرح له يمكنه إجراء العملية
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
            'name'   => 'required|string|max:255',
            'code'   => 'required|string|max:50|unique:symbols,code,' . $this->route('symbol'),
            'type'   => 'required|string|max:50',
            'status' => 'required|boolean',
        ];
    }

    /**
     * Optional: custom messages
     */
    public function messages()
    {
        return [
            'name.required'   => 'يجب إدخال الاسم',
            'code.required'   => 'يجب إدخال الكود',
            'code.unique'     => 'هذا الكود مستخدم من قبل',
            'type.required'   => 'يجب إدخال النوع',
            'status.required' => 'يجب تحديد الحالة',
        ];
    }
}
