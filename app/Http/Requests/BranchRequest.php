<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;

class BranchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
       // Check if the authenticated user has the necessary role
       if (auth()->user()->type == "admin" || auth()->user()->type == "Superadmin") {
        return true;
    }

    return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
			'brand_id' => 'required',
			'phone' => 'required|string',
			'email' => 'required|email',
			'address' => 'required|string',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => "Error en datos requeridos",
            'data' => $validator->errors()
        ], Response::HTTP_BAD_REQUEST));
    }

    public function messages(): array
    {
        return [
            "brand_id.required" => "El ID de la Marca es requerido",
            "address.required" => "La dirección es requerida",
            "email.required" => "El correo es requerido",
            "email.email" => "Debe ser un correo valido",
            "phone.required" => "El teléfono es requerido",
        ];
    }
}
