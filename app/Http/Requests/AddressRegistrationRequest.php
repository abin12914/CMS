<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddressRegistrationRequest extends FormRequest
{
    public $accountId = '';

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
            'name'          =>  [
                                    'nullable',
                                    'min:3',
                                    'max:100',
                                ],
            'designation'   =>  [
                                    'required',
                                    'min:3',
                                    'max:100',
                                ],
            'address'       =>  [
                                    'required',
                                    'min:3',
                                    'max:255',
                                ],
            'title'         =>  [
                                    'required',
                                    'min:3',
                                    'max:50',
                                ],
        ];
    }
}
