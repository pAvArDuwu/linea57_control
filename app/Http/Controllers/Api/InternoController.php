<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Interno;
use Illuminate\Http\Request;

class InternoController extends Controller
{
    public function index()
    {
        return response()->json(Interno::where('estado', '!=', 'inactivo')->get());
    }

    public function store(Request $request)
    {
        $interno = Interno::create($request->all());

        return response()->json($interno, 201);
    }

    public function show(string $id)
    {
        return response()->json(Interno::findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $interno = Interno::findOrFail($id);
        $interno->update($request->all());

        return response()->json($interno);
    }

    public function destroy(string $id)
    {
        $interno = Interno::findOrFail($id);
        $interno->update(['estado' => 'inactivo']);

        return response()->json(['message' => 'Interno desactivado correctamente']);
    }
}
