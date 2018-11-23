<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\University;

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
            'university_id'     =>  [
                                        'required',
                                        Rule::in(University::pluck('id')->toArray()),
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
