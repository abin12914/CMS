<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Course;

class BatchRegistrationRequest extends FormRequest
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
            'batch_name'    =>  [
                                    'required',
                                    'min:3',
                                    'max:100',
                                    Rule::unique('batches')->ignore($this->batch),
                                ],
            'course_id'     =>  [
                                    'required',
                                    Rule::in(Course::pluck('id')->toArray()),
                                ],
            'from_year'     =>  [
                                    'required',
                                    'digits:4',
                                    'integer',
                                    'min:1990',
                                    'max:2030',
                                ],
            'to_year'       =>  [
                                    'required',
                                    'digits:4',
                                    'integer',
                                    'min:1990',
                                    'max:2030',
                                ],
            'fee_amount'    =>  [
                                    'required',
                                    'numeric',
                                    'min:1',
                                    'max:999999',
                                ],
            'fee_per_year'  =>  [
                                    'nullable',
                                    'numeric',
                                    'min:1',
                                    'max:999999',
                                ],
            'fee_per_sem'   =>  [
                                    'nullable',
                                    'numeric',
                                    'min:1',
                                    'max:999999',
                                ],
            'fee_per_month' =>  [
                                    'nullable',
                                    'numeric',
                                    'min:1',
                                    'max:999999',
                                ],
        ];
    }
}
