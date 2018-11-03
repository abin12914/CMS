<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Authority;

class CertificateRegistrationRequest extends FormRequest
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
            'name'                  =>  [
                                            'required',
                                            'min:3',
                                            'max:100',
                                        ],
            'description'           =>  [
                                            'required',
                                            'min:3',
                                            'max:255',
                                        ],
            'authority_id'          =>  [
                                            'required',
                                            Rule::in(Authority::pluck('id')->toArray()),
                                        ],
            'certificate_type'      =>  [
                                            'required',
                                            Rule::in([1, 2]),
                                        ],
            'certificate_content'   =>  [
                                            'required',
                                            'string',
                                            'min:20',
                                            'max:2000',
                                        ],
        ];
    }
}
