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
            'surname' => 'required|string|max:50',
            'name' => 'required|string|max:30',
            'email' => 'required|email|max:50', 
            'birthdate' => 'required|date|before:'. date('Y-m-d'),
            'division' => 'required|int',
            'is_leadership' =>'required|boolean',
            'is_management' =>'required|boolean',
        ];
    }
}
