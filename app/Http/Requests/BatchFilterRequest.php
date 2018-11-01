<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Batch;
use App\Models\Course;

class BatchFilterRequest extends FormRequest
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
            'batch_id'      =>  [
                                    'nullable',
                                    Rule::in(Batch::pluck('id')->toArray()),
                                ],
            'course_id'     =>  [
                                    'nullable',
                                    Rule::in(Course::pluck('id')->toArray()),
                                ],
            'from_year'     =>  [
                                    'nullable',
                                    'digits:4',
                                    'integer',
                                    'min:1990',
                                    'max:2030',
                                ],
            'to_year'       =>  [
                                    'nullable',
                                    'digits:4',
                                    'integer',
                                    'min:1990',
                                    'max:2030',
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
