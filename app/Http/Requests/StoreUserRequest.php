<?php

namespace App\Http\Requests;

use App\Rules\StrictPassword;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('admin') ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', 'string', 'confirmed', new StrictPassword],
            'role' => ['required', Rule::in(['admin', 'operator'])],
        ];
    }

    public function messages(): array
    {
        return [
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ];
    }
}
