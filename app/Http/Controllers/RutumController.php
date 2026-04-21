<?php

namespace App\Http\Controllers;

use App\Models\Rutum;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\RutumRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class RutumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $ruta = Rutum::paginate();

        return view('rutum.index', compact('ruta'))
            ->with('i', ($request->input('page', 1) - 1) * $ruta->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $rutum = new Rutum();

        return view('rutum.create', compact('rutum'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RutumRequest $request): RedirectResponse
    {
        Rutum::create($request->validated());

        return Redirect::route('ruta.index')
            ->with('success', 'Rutum created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $rutum = Rutum::find($id);

        return view('rutum.show', compact('rutum'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $rutum = Rutum::find($id);

        return view('rutum.edit', compact('rutum'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RutumRequest $request, Rutum $rutum): RedirectResponse
    {
        $rutum->update($request->validated());

        return Redirect::route('ruta.index')
            ->with('success', 'Rutum updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Rutum::find($id)->delete();

        return Redirect::route('ruta.index')
            ->with('success', 'Rutum deleted successfully');
    }
}
