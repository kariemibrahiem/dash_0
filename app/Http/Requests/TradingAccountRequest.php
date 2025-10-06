<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TradingAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            return $this->update();
        }

        return $this->store();
    }

    protected function store(): array
    {
        return [
            'user_id'       => ['required', 'exists:users,id'],
            'account_name'  => ['required', 'string', 'max:255'],
            'api_key'       => ['required', 'string', 'max:255', 'unique:trading_accounts,api_key'],
            'api_secret'    => ['required', 'string', 'max:255'],
            'account_type'  => ['required', 'in:demo,real'],
            'balance'       => ['nullable', 'numeric', 'min:0'],
            'status'        => ['required', 'in:1,0'],
            'last_sync'     => ['nullable', 'date'],
        ];
    }

    protected function update(): array
    {
        return [
            'user_id'       => ['required', 'exists:users,id'],
            'account_name'  => ['required', 'string', 'max:255'],
            'api_key'       => [
                'required',
                'string',
                'max:255',
                'unique:trading_accounts,api_key,' . $this->route('trading_account')
            ],
            'api_secret'    => ['required', 'string', 'max:255'],
            'account_type'  => ['required', 'in:demo,real'],
            'balance'       => ['nullable', 'numeric', 'min:0'],
            'status'        => ['required', 'in:1,0'],
            'last_sync'     => ['nullable', 'date'],
        ];
    }
}
