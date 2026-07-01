<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => [
                'required',
                'email',
                'min:1',
                'max:200',
                Rule::unique('users', 'email')->ignore($this->employee_updating_id, 'id')  
            ],
            
            'first_name'      => ['required', 'string', 'max:100'],
            'last_name'      => ['required', 'string', 'max:100'],
            'password'  => ['nullable', 'string', 'min:6'],
        ];
    }
}
