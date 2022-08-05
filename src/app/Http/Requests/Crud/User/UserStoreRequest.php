<?php

namespace Different\DifferentCore\app\Http\Requests\Crud\User;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'unique:'.config('permission.table_names.users', 'users').',email'],
            'name' => ['required'],
            'password' => ['required', 'confirmed'],
            'roles' => ['nullable'],
            'phone' => ['nullable', 'string'],
        ];
    }
}
