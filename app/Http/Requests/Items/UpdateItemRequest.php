<?php

namespace App\Http\Requests\Items;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
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
            'supplier_id' => ['required', 'numeric', 'exists:suppliers,id'],
            'type_id' => ['required', 'numeric', 'exists:types,id'],
            'sku' => ['required', 'string', 'min:2', 'max:255', 'unique:items,sku,'.$this->item->id],
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'critical_level' => ['required', 'numeric', 'min:0']
        ];
    }
}
