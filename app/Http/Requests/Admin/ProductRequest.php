<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if(Request::routeIs('product.store'))
        {
            return [
                'warehouse_id' => ['required', 'integer'],
                'name' => ['required', 'string', 'unique:products,name'],
                'description' => ['required', 'string'],
                'price' => ['required', 'numeric', 'min:0'], // Ensure price is numeric and non-negative
                'qty' => ['required', 'integer', 'min:0'],   // Ensure qty is an integer and non-negative
            ];
        }

        if(Request::routeIs('product.update'))
        {
            return [
                'warehouse_id' => ['sometimes', 'required', 'integer'], // Validate only if provided
                'name' => ['sometimes', 'required', 'string', 'unique:products,name,' . Request::route('product')], // Validate only if provided
                'description' => ['sometimes', 'required', 'string'], // Validate only if provided
                'price' => ['sometimes', 'numeric', 'min:0'],         // Ensure price is numeric and non-negative
                'qty' => ['sometimes', 'integer', 'min:0'],
            ];
        }


        return [
            //
        ];
    }
}