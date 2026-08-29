<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conductor;
use App\Http\Requests\Api\StoreConductorRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ConductorController extends Controller
{
    public function index()
    {
        return response()->json(Conductor::where('estado', '!=', 'inactivo')->get());
    }

    public function store(StoreConductorRequest $request)
    {
        $conductor = Conductor::create($request->validated());

        return response()->json($conductor, 201);
    }

    public function show(string $id)
    {
        return response()->json(Conductor::findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $conductor = Conductor::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:30',
            'apellido' => 'required|string|max:30',
            'telefono' => 'required|string|max:15',
            'correo' => [
                'required',
                'email',
                'max:50',
                Rule::unique('conductor', 'correo')->ignore($conductor->id),
            ],
            'ci' => [
                'required',
                'string',
                'max:20',
                Rule::unique('conductor', 'ci')->ignore($conductor->id),
            ],
            'estado' => 'required|in:activo,inactivo',
        ]);

        $conductor->update($validated);

        return response()->json($conductor);
    }

    public function destroy(string $id)
    {
        $conductor = Conductor::findOrFail($id);
        $conductor->update(['estado' => 'inactivo']);

        return response()->json(['message' => 'Conductor desactivado correctamente']);
    }
}
