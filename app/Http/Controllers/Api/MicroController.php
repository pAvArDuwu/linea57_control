<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Micro;
use Illuminate\Http\Request;

class MicroController extends Controller
{
    public function index()
    {
        return response()->json(Micro::where('estado', '!=', 'inactivo')->get());
    }

    public function store(Request $request)
    {
        $micro = Micro::create($request->all());

        return response()->json($micro, 201);
    }

    public function show(string $id)
    {
        return response()->json(Micro::findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $micro = Micro::findOrFail($id);
        $micro->update($request->all());

        return response()->json($micro);
    }

    public function destroy(string $id)
    {
        $micro = Micro::findOrFail($id);
        $micro->update(['estado' => 'inactivo']);

        return response()->json(['message' => 'Micro desactivado correctamente']);
    }
}
