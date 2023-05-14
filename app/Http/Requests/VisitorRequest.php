<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VisitorRequest extends FormRequest
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
        if ($this->visitor) {
            $email    = ['required', 'email', 'string'];//, Rule::unique("visitors", "email")->ignore($this->visitor->visitor_id)
        } else {
            $email    = ['required', 'email', 'string'];//, 'unique:visitors,email'
        }
        $array_values = ['T','P','C'];
        return [
            'first_name'                => 'required|string|max:100',
            'last_name'                 => 'required|string|max:100',
            // 'email'                     => $email,
            'phone'                     => 'nullable|string|max:20',
            'employee_id'               => 'required|numeric',
            'gender'                    => 'required|numeric',
            'company_name'              => 'nullable|max:100',
            'national_identification_no'=> 'nullable|max:100',
            // 'car_type'                  => [ 'required' , Rule::in($this->array_values) ],
            // 'car_type'                  => 'required|in_array:array_values.*',
            'car_type'                  => 'required|string',
            'purpose'                   => 'required|max:191',
            'address'                   => 'nullable|max:191',
            'shipment_id'               => 'nullable|numeric|exists:shipments,id',
            'shipment_number'           => 'nullable|numeric',
            'image'                     => 'nullable|image|mimes:jpeg,png,jpg|max:5098',
        ];
    }
}
