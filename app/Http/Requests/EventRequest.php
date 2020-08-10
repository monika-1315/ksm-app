<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
            'id' => 'numeric',
            'title' => 'required|string|max:100',
            'about' => 'required|string|max:300',
            'start' => 'required|string|date',
            'end' => 'required|string|date|after_or_equal:start',
            'division' => 'required|int',
            'location' => 'required|string|max:100',
            // 'price' => 'string|max:100',
            // 'timetable' => 'string|max:2000',
            // 'details' => 'string|max:1000',
            'author' => 'numeric',
        ];
    }
}
