<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseRegistrationRequest extends FormRequest
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
        $courseDurationTypes  = config('constants.courseDurationTypes');

        return [
            'name'              =>  [
                                        'required',
                                        'min:2',
                                        'max:100',
                                    ],
            'descriptive_name'  =>  [
                                        'required',
                                        'min:2',
                                        'max:255',
                                    ],
            'university'        =>  [
                                        'required',
                                        'min:2',
                                        'max:255',
                                    ],
            'center_code'       =>  [
                                        'required',
                                        'min:2',
                                        'max:100',
                                    ],
            'duration'          =>  [
                                        'required',
                                        'min:1',
                                        'max:99',
                                    ],
            'duration_type'     =>  [
                                        'required',
                                        Rule::in(array_keys($courseDurationTypes)),
                                    ],
        ];
    }
}
