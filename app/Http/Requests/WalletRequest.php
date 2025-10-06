<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WalletRequest extends FormRequest
{
    public function authorize()
    {
        return true; // نفترض كل الأدمنز مسموح لهم
    }

    public function rules()
    {
        if ($this->isMethod('put')) {
            return $this->update();
        } else {
            return $this->store();
        }
    }

    protected function store(): array
    {
        return [
            'user_id'  => ['required', 'exists:users,id'],
            'currency' => ['required', 'string', 'max:10'],
            'balance'  => ['required', 'numeric', 'min:0'],
            'status'   => ['required', 'in:1,0'],
        ];
    }

    protected function update(): array
    {
        // نفس قواعد الإنشاء غالبًا
        return [
            'user_id'  => ['required', 'exists:users,id'],
            'currency' => ['required', 'string', 'max:10'],
            'balance'  => ['required', 'numeric', 'min:0'],
            'status'   => ['required', 'in:1,0'],
        ];
    }
}
