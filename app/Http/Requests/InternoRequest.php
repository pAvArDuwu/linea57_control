<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InternoRequest extends FormRequest
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
            'numero_interno' => 'required|string|max:10',
            'fecha_ingreso' => 'required',
            'observaciones' => 'nullable|string',
            'estado' => 'required|in:disponible,asignado,inactivo',
        ];
    }
}
