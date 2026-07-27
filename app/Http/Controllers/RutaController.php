<?php

namespace App\Http\Controllers;

use App\Models\Ruta;
use App\Models\parada;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RutaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $buscar = $request->input('buscar');
        $rutas = Ruta::withCount('paradas')
                     ->when($buscar, function ($query, $buscar) {
                         return $query->where('nombre', 'LIKE', "%{$buscar}%")
                                      ->orWhere('descripcion', 'LIKE', "%{$buscar}%");
                     })
                     ->paginate(10);

        return view('ruta.index', compact('rutas', 'buscar'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $ruta = new Ruta();
        $paradas = parada::where('estado', 'activo')->orderBy('nombre')->get();
        $paradasSeleccionadas = collect();

        return view('ruta.create', compact('ruta', 'paradas', 'paradasSeleccionadas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'descripcion' => 'nullable|string',
            'sentido' => 'required|in:Ida,Vuelta',
            'estado' => 'required|in:activo,inactivo',
            'paradas' => 'nullable|array',
            'paradas.*' => 'exists:paradas,id',
        ]);

        $ruta = Ruta::create($request->only(['nombre', 'descripcion', 'sentido', 'estado']));

        // Sync paradas with automatic order
        if ($request->has('paradas') && is_array($request->paradas)) {
            $syncData = [];
            foreach ($request->paradas as $index => $paradaId) {
                $syncData[$paradaId] = [
                    'orden' => $index + 1,
                    'estado' => 'activo',
                ];
            }
            $ruta->paradas()->sync($syncData);
        }

        return Redirect::route('ruta.index')
            ->with('success', 'Ruta creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $ruta = Ruta::with(['paradas' => function ($query) {
            $query->orderByPivot('orden');
        }])->findOrFail($id);

        return view('ruta.show', compact('ruta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $ruta = Ruta::findOrFail($id);
        $paradas = parada::where('estado', 'activo')->orderBy('nombre')->get();
        $paradasSeleccionadas = $ruta->paradas()->orderByPivot('orden')->get();

        return view('ruta.edit', compact('ruta', 'paradas', 'paradasSeleccionadas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $ruta = Ruta::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:50',
            'descripcion' => 'nullable|string',
            'sentido' => 'required|in:Ida,Vuelta',
            'estado' => 'required|in:activo,inactivo',
            'paradas' => 'nullable|array',
            'paradas.*' => 'exists:paradas,id',
        ]);

        $ruta->update($request->only(['nombre', 'descripcion', 'sentido', 'estado']));

        // Sync paradas with automatic order
        $syncData = [];
        if ($request->has('paradas') && is_array($request->paradas)) {
            foreach ($request->paradas as $index => $paradaId) {
                $syncData[$paradaId] = [
                    'orden' => $index + 1,
                    'estado' => 'activo',
                ];
            }
        }
        $ruta->paradas()->sync($syncData);

        return Redirect::route('ruta.index')
            ->with('success', 'Ruta actualizada exitosamente.');
    }

    public function destroy($id): RedirectResponse
    {
        Ruta::findOrFail($id)->delete();

        return Redirect::route('ruta.index')
            ->with('success', 'Ruta eliminada exitosamente.');
    }
}
