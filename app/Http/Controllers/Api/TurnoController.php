<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Turno;
use Illuminate\Http\Request;

class TurnoController extends Controller
{
    public function index()
    {
        return response()->json(Turno::where('estado', '!=', 'inactivo')->get());
    }

    public function store(Request $request)
    {
        $turno = Turno::create($request->all());

        return response()->json($turno, 201);
    }

    public function show(string $id)
    {
        return response()->json(Turno::findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $turno = Turno::findOrFail($id);
        $turno->update($request->all());

        return response()->json($turno);
    }

    public function destroy(string $id)
    {
        $turno = Turno::findOrFail($id);
        $turno->update(['estado' => 'inactivo']);

        return response()->json(['message' => 'Turno desactivado correctamente']);
    }
}
