<?php

namespace App\Http\Controllers;

use App\Models\Micro;
use App\Models\Dueño;
use App\Models\Interno;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\MicroRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class MicroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $buscar = $request->input('buscar');
        $micros = Micro::with(['propietario', 'interno'])
                       ->when($buscar, function ($query, $buscar) {
                           return $query->where('placa', 'LIKE', '%' . $buscar . '%')
                                        ->orWhere('modelo', 'LIKE', '%' . $buscar . '%')
                                        ->orWhere('marca', 'LIKE', '%' . $buscar . '%')
                                        ->orWhereHas('propietario', function ($q) use ($buscar) {
                                            $q->where('nombre', 'LIKE', "%{$buscar}%")
                                              ->orWhere('apellido', 'LIKE', "%{$buscar}%");
                                        });
                       })
                       ->paginate(10);

        return view('micro.index', compact('micros', 'buscar'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $micro = new Micro();
        $propietarios = Dueño::where('estado', 'activo')->orderBy('nombre')->get();
        $internos = Interno::whereIn('estado', ['disponible', 'activo'])->orderBy('numero_interno')->get();

        return view('micro.create', compact('micro', 'propietarios', 'internos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MicroRequest $request): RedirectResponse
    {
        $micro = Micro::create($request->validated());

        // Update interno estado to asignado if linked
        if ($micro->interno_id) {
            Interno::where('id', $micro->interno_id)->update(['estado' => 'asignado']);
        }

        return Redirect::route('micro.index')
            ->with('success', 'Micro creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $micro = Micro::with(['propietario', 'interno'])->findOrFail($id);

        return view('micro.show', compact('micro'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $micro = Micro::findOrFail($id);
        $propietarios = Dueño::orderBy('nombre')->get();
        $internos = Interno::orderBy('numero_interno')->get();

        return view('micro.edit', compact('micro', 'propietarios', 'internos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MicroRequest $request, $id): RedirectResponse
    {
        $micro = Micro::findOrFail($id);
        $oldInternoId = $micro->interno_id;

        $micro->update($request->validated());

        if ($oldInternoId && $oldInternoId != $micro->interno_id) {
            Interno::where('id', $oldInternoId)->update(['estado' => 'disponible']);
        }
        if ($micro->interno_id) {
            Interno::where('id', $micro->interno_id)->update(['estado' => 'asignado']);
        }

        return Redirect::route('micro.index')
            ->with('success', 'Micro actualizado correctamente.');
    }

    /**
     * Logical delete: set estado to inactivo
     */
    public function destroy($id): RedirectResponse
    {
        $micro = Micro::findOrFail($id);
        if ($micro->interno_id) {
            Interno::where('id', $micro->interno_id)->update(['estado' => 'disponible']);
        }
        $micro->update(['estado' => 'inactivo']);

        return Redirect::route('micro.index')
            ->with('success', 'Micro desactivado correctamente.');
    }
}
