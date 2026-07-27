<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MicroRequest extends FormRequest
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
            'propietario_id' => 'required|exists:propietarios,id',
            'interno_id' => 'nullable|exists:interno,id',
            'placa' => 'required|string|max:20',
            'chasis' => 'nullable|string|max:50',
            'anio_fabricacion' => 'nullable|integer',
            'modelo' => 'required|string|max:30',
            'marca' => 'required|string|max:30',
            'capacidad_pasajeros' => 'required|integer',
            'estado' => 'required|in:activo,inactivo',
        ];
    }
}
