<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;


class UpdateBrandRequest extends FormRequest
{
    public mixed $property;
    public mixed $name;
    public mixed $phone;
    public mixed $email;
    public mixed $address;

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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'property' => 'required',
            'name' => ['required', Rule::unique('brands')->ignore($this->route('brand'), 'id')],
            'phone' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'user_id' => 'required'
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
            "user_id.required" => "El propietario es requerido",
            "name.required" => "El nombre es requerido",
            "name.unique" => "El nombre ya ha sido utilizado",
            "address.required" => "La dirección es requerida",
            "email.required" => "El correo es requerido",
            "phone.required" => "El teléfono es requerido",
            "property.required" => "El propietario es requerido",
        ];
    }
}
