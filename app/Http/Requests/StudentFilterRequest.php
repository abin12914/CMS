<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Student;

class StudentFilterRequest extends FormRequest
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
        $genderTypes = config('constants.genderTypes');

        return [
            'relation_type' =>  [
                                    'nullable',
                                    Rule::in(array_keys($genderTypes)),
                                ],
            'student_id'    =>  [
                                    'nullable',
                                    Rule::in(Student::pluck('id')->toArray()),
                                ],
            'no_of_records' =>  [
                                    'nullable',
                                    'min:2',
                                    'max:100',
                                    'integer',
                                ],
            'page'          =>  [
                                    'nullable',
                                    'integer',
                                ],
        ];
    }
}
