<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dueño;
use Illuminate\Http\Request;

class PropietarioController extends Controller
{
    public function index()
    {
        return response()->json(Dueño::all());
    }

    public function store(Request $request)
    {
        $propietario = Dueño::create($request->all());

        return response()->json($propietario, 201);
    }

    public function show(string $id)
    {
        return response()->json(Dueño::findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $propietario = Dueño::findOrFail($id);
        $propietario->update($request->all());

        return response()->json($propietario);
    }

    public function destroy(string $id)
    {
        Dueño::destroy($id);

        return response()->json(['message' => 'Propietario eliminado']);
    }
}
