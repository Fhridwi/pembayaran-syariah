<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
            'nama_lengkap' => 'required|max:100',
            'username'     => 'required|unique:users,username',
            'email'        => 'required|email|unique:users,email',
            'no_hp'        => 'required|unique:users,no_hp',
            'alamat'       => 'nullable',
            'password'     => 'required',
            'role'         => 'required|in:admin,user,bendahara,pengasuh',
        ];
    }
}
