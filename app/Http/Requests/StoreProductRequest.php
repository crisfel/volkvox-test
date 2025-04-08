<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class StoreProductRequest extends FormRequest
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
        return [
            'name' => 'required|string',
            'price' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es requerido',
            'name.string' => 'El nombre debe ser de tipo string',
            'price.required' => 'El precio es requerido',
            'price.numeric' => 'El precio debe ser de tipo númerico',
        ];
    }


    public function failedValidation(Validator $validator): never
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY));
    }
}
