<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\parada;
use Illuminate\Http\Request;

class ParadaController extends Controller
{
    public function index()
    {
        return response()->json(parada::all());
    }

    public function store(Request $request)
    {
        $parada = parada::create($request->all());

        return response()->json($parada, 201);
    }

    public function show(string $id)
    {
        return response()->json(parada::findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $parada = parada::findOrFail($id);
        $parada->update($request->all());

        return response()->json($parada);
    }

    public function destroy(string $id)
    {
        parada::destroy($id);

        return response()->json(['message' => 'Parada eliminada']);
    }
}
