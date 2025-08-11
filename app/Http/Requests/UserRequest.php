<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
     */
    public function rules(): array
    {
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            return $this->updateRules();
        }

        return $this->storeRules();
    }

    /**
     * Validation rules for storing a user.
     */
    protected function storeRules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'status'   => 'nullable|boolean',
            'image'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }

    /**
     * Validation rules for updating a user.
     */
    protected function updateRules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $this->route('user'),
            'password' => 'nullable|min:8|confirmed',
            'status'   => 'nullable|boolean',
            'image'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }
}
