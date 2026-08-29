<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ruta;
use Illuminate\Http\Request;

class RutaController extends Controller
{
    public function index()
    {
        return response()->json(Ruta::where('estado', '!=', 'inactivo')->get());
    }

    public function store(Request $request)
    {
        $ruta = Ruta::create($request->all());

        return response()->json($ruta, 201);
    }

    public function show(string $id)
    {
        return response()->json(Ruta::findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $ruta = Ruta::findOrFail($id);
        $ruta->update($request->all());

        return response()->json($ruta);
    }

    public function destroy(string $id)
    {
        $ruta = Ruta::findOrFail($id);
        $ruta->update(['estado' => 'inactivo']);

        return response()->json(['message' => 'Ruta desactivada correctamente']);
    }
}
