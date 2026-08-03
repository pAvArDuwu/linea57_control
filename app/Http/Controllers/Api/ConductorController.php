<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conductor;
use Illuminate\Http\Request;

class ConductorController extends Controller
{
    public function index()
    {
        return response()->json(Conductor::all());
    }

    public function store(Request $request)
    {
        $conductor = Conductor::create($request->all());

        return response()->json($conductor, 201);
    }

    public function show(string $id)
    {
        return response()->json(Conductor::findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $conductor = Conductor::findOrFail($id);
        $conductor->update($request->all());

        return response()->json($conductor);
    }

    public function destroy(string $id)
    {
        Conductor::destroy($id);

        return response()->json(['message' => 'Conductor eliminado']);
    }
}
