<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Batch;

class StudentRegistrationRequest extends FormRequest
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
        $genderTypes  = config('constants.genderTypes');
        
        return [
            'student_code'  =>  [
                                    'required',
                                    'min:3',
                                    'max:20',
                                    Rule::unique('students')->ignore($this->student),
                                ],
            'name'          =>  [
                                    'required',
                                    'min:3',
                                    'max:100',
                                ],
            'address'       =>  [
                                    'nullable',
                                    'max:200',
                                ],
            'phone'         =>  [
                                    'required',
                                    'numeric',
                                    'digits_between:10,13',
                                    Rule::unique('students')->ignore($this->student),
                                ],
            'gender'        =>  [
                                    'required',
                                    Rule::in(array_keys($genderTypes)),
                                ],
            'title'         =>  [
                                    'required',
                                    'min:2',
                                    'max:100',
                                ],
            'batch_id'     =>  [
                                    'required',
                                    Rule::in(Batch::pluck('id')->toArray()),
                                ],
        ];
    }
}
