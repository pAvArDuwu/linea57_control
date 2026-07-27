<?php

namespace App\Http\Controllers;

use App\Models\parada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ParadaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        $paradas = parada::when($buscar, function ($query, $buscar) {
            return $query->where('nombre', 'LIKE', "%{$buscar}%")
                         ->orWhere('referencia', 'LIKE', "%{$buscar}%");
        })->paginate(12);

        return view('parada.index', compact('paradas', 'buscar'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parada = new parada();
        return view('parada.create', compact('parada'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'referencia' => 'nullable|string|max:255',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'estado' => 'required|in:activo,inactivo',
        ]);

        parada::create($request->all());

        return Redirect::route('parada.index')->with('success', 'Parada creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(parada $parada)
    {
        return view('parada.show', compact('parada'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(parada $parada)
    {
        return view('parada.edit', compact('parada'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, parada $parada)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'referencia' => 'nullable|string|max:255',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $parada->update($request->all());

        return Redirect::route('parada.index')->with('success', 'Parada actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage (Logical delete).
     */
    public function destroy(parada $parada)
    {
        $parada->update(['estado' => 'inactivo']);

        return Redirect::route('parada.index')->with('success', 'Parada desactivada correctamente.');
    }
}
