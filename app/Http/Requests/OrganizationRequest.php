<?php

namespace App\Http\Requests;

use App\Models\Organization;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizationRequest extends FormRequest
{ use HandlesAuthorization;
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
            'name'                =>   'required|min:2|max:100',
            'email'               =>   'required|email',
            'postal_code'         =>   'nullable|numeric',
            'organization_logo'   =>   'nullable|image',
        ];
    }
}
