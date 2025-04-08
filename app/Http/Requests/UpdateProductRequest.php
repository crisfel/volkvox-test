<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UpdateProductRequest extends FormRequest
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
            'id' => 'required|numeric',
            'name' => 'string',
            'price' => 'numeric',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'El ID del producto es requerido',
            'id.numeric' => 'El ID del producto debe ser de tipo numérico',
            'name.string' => 'El nombre debe ser de tipo string',
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
