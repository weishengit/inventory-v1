<?php

namespace App\Http\Requests\ReleaseOrder;

use Illuminate\Foundation\Http\FormRequest;

class StoreReleaseOrderRequest extends FormRequest
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
            'released_to' => ['required', 'string', 'max:255'],
            'ro_num' => ['required', 'string', 'max:255'],
            'memo' => ['nullable', 'string', 'max:1000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*' => ['required', 'numeric']
        ];
    }
}
