<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UniversityRegistrationRequest extends FormRequest
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
            'university_name'   =>  [
                                        'required',
                                        'min:3',
                                        'max:100',
                                        Rule::unique('universities')->ignore($this->university),
                                    ],
            'center_code'       =>  [
                                        'required',
                                        'min:3',
                                        'max:100',
                                    ],
            'university_grade'  =>  [
                                        'required',
                                        'min:3',
                                        'max:100'
                                    ],
        ];
    }
}
