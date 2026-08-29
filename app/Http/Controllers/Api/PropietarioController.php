<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Propietario;
use Illuminate\Http\Request;

class PropietarioController extends Controller
{
    public function index()
    {
        return response()->json(Propietario::where('estado', '!=', 'inactivo')->get());
    }

    public function store(Request $request)
    {
        $propietario = Propietario::create($request->all());

        return response()->json($propietario, 201);
    }

    public function show(string $id)
    {
        return response()->json(Propietario::findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $propietario = Propietario::findOrFail($id);
        $propietario->update($request->all());

        return response()->json($propietario);
    }

    public function destroy(string $id)
    {
        $propietario = Propietario::findOrFail($id);
        $propietario->update(['estado' => 'inactivo']);

        return response()->json(['message' => 'Propietario desactivado correctamente']);
    }
}
