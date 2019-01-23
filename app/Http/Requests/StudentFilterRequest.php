<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Student;
use App\Models\Batch;

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
        return [
            'batch_id' =>  [
                                    'nullable',
                                    Rule::in(Batch::pluck('id')->toArray()),
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
