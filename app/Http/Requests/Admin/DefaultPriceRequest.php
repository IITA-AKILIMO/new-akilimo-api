<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DefaultPriceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isCreate = $this->isMethod('POST');

        return [
            'country' => [$isCreate ? 'required' : 'sometimes|required', 'string', 'size:2'],
            'item' => [$isCreate ? 'required' : 'sometimes|required', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:15'],
            'currency' => ['nullable', 'string', 'size:3'],
        ];
    }
}
