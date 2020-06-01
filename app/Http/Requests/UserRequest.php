<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'id' => 'required|numeric',
            'surname' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|email', 
            // 'password' => 'string|min:6',
            // 'confirmPassword' => 'string|min:6|same:password',
            'birthdate' => 'required|date',
            'division' => 'required|int',
            'is_leadership' =>'required|boolean',
            'is_management' =>'required|boolean',
        ];
    }
}
