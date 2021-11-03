<?php

namespace App\Http\Requests\Suppliers;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
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
            'tin' => ['required', 'numeric', 'max:255'],
            'bir' => ['required', 'numeric', 'max:255'],
            'vat' => ['required', 'numeric', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255', 'min:3'],
            'company_name' => ['required', 'string', 'max:255', 'min:3'],
            'address' => ['required', 'string', 'max:255'],
            'contact' => ['required', 'numeric', 'max:255'],
            'email' => ['required', 'email', 'unique:suppliers,email,'.$this->supplier->id],
        ];
    }
}
