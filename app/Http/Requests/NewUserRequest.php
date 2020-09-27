<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewUserRequest extends FormRequest
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
            'surname' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|email|unique:users', 
            'birthdate' => 'required|date|before:'. date('Y-m-d'),
            'division' => 'required|int',
            'is_leadership' =>'required|boolean',
        ];
    }
}
