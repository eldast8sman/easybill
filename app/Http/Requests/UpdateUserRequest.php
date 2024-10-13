<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $id = $this->route('id');
        return [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => [
                'required', 'string', 'email',
                Rule::unique('users', 'email')->ignore($id, 'id')
            ],
            'phone' => [
                'required', 'string',
                Rule::unique('users', 'phone')->ignore($id, 'id')
            ]
        ];
    }
}
