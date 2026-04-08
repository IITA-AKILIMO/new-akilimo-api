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
        $required = $this->isMethod('POST') ? 'required' : 'sometimes|required';

        return [
            'country' => [$required, 'string', 'size:2'],
            'item' => [$required, 'string', 'max:50'],
            'price' => [$required, 'numeric', 'min:0'],
            'unit' => [$required, 'string', 'max:15'],
            'currency' => ['nullable', 'string', 'size:3'],
        ];
    }
}
