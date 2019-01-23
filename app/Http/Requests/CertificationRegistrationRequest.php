<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Certificate;
use App\Models\Address;
use App\Models\Student;

class CertificationRegistrationRequest extends FormRequest
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
            'certificate_date'  =>  [
                                        'required',
                                        'date_format:d-m-Y'
                                    ],
            'certificate_id'    =>  [
                                        'required',
                                        Rule::in(Certificate::pluck('id')->toArray()),
                                    ],
            'address_id'        =>  [
                                        'required',
                                        Rule::in((array_merge(Address::pluck('id')->toArray(), [-1]))),
                                    ],
            'student_id'        =>  [
                                        'required',
                                        'array',
                                        'min:1',
                                        'max:50'
                                    ],
            'student_id.*'      =>  [
                                        Rule::in(Student::pluck('id')->toArray()),
                                    ],
        ];
    }
}
