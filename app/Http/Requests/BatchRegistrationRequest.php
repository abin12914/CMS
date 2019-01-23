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
            'batch_name'        =>  [
                                        'required',
                                        'min:3',
                                        'max:100',
                                        Rule::unique('batches')->ignore($this->batch),
                                    ],
            'course_id'         =>  [
                                        'required',
                                        Rule::in(Course::pluck('id')->toArray()),
                                    ],
            'from_year'         =>  [
                                        'required',
                                        'digits:4',
                                        'integer',
                                        'min:1990',
                                        'max:2030',
                                    ],
            'to_year'           =>  [
                                        'required',
                                        'digits:4',
                                        'integer',
                                        'min:1990',
                                        'max:2030',
                                    ],
            'fee_amount'        =>  [
                                        'required',
                                        'numeric',
                                        'min:1',
                                        'max:999999',
                                    ],
            'fee_per_year'      =>  [
                                        'nullable',
                                        'numeric',
                                        'min:1',
                                        'max:999999',
                                    ],
            'fee_per_sem'       =>  [
                                        'nullable',
                                        'numeric',
                                        'min:1',
                                        'max:999999',
                                    ],
            'fee_per_month'     =>  [
                                        'nullable',
                                        'numeric',
                                        'min:1',
                                        'max:999999',
                                    ],
            'class_start_date'  =>  [
                                        'required',
                                        'date_format:d-m-Y'
                                    ],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->from_year > $this->to_year) {
                $validator->errors()->add('to_year', 'To year must be less than or equal to from year!');
            }

            if ($this->fee_amount < $this->fee_per_year) {
                $validator->errors()->add('fee_per_year', 'Invalid data! Must be less than total course fee.');
            }

            if ($this->fee_amount < $this->fee_per_sem) {
                $validator->errors()->add('fee_per_sem', 'Invalid data! Must be less than total course fee.');
            }

            if ($this->fee_amount < $this->fee_per_month) {
                $validator->errors()->add('fee_per_month', 'Invalid data! Must be less than total course fee.');
            }
        });
    }
}
