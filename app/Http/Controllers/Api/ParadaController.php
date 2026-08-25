<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Parada;
use Illuminate\Http\Request;

class ParadaController extends Controller
{
    public function index()
    {
        return response()->json(Parada::all());
    }

    public function store(Request $request)
    {
        $parada = Parada::create($request->all());

        return response()->json($parada, 201);
    }

    public function show(string $id)
    {
        return response()->json(Parada::findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $parada = Parada::findOrFail($id);
        $parada->update($request->all());

        return response()->json($parada);
    }

    public function destroy(string $id)
    {
        Parada::destroy($id);

        return response()->json(['message' => 'Parada eliminada']);
    }
}
