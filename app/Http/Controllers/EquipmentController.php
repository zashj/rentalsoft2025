<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EquipmentController extends Controller
{
    public function index(): View
    {
        if (!auth()->user()->hasPermission('read')) {
            abort(403, 'No tienes permisos para ver equipos');
        }
        
        $equipment = Equipment::where('is_active', true)->orderBy('name')->get();
        return view('equipment.index', compact('equipment'));
    }

    public function show(Equipment $equipment): View
    {
        if (!auth()->user()->hasPermission('read')) {
            abort(403, 'No tienes permisos para ver equipos');
        }

        return view('equipment.show', compact('equipment'));
    }

    public function create(): View
    {
        if (!auth()->user()->hasPermission('write')) {
            abort(403, 'No tienes permisos para crear equipos');
        }
        
        return view('equipment.create');
    }

    public function store(Request $request): RedirectResponse
    {
        if (!auth()->user()->hasPermission('write')) {
            abort(403, 'No tienes permisos para crear equipos');
        }

        $validated = $request->validate([
            'serial_number' => 'required|unique:equipment',
            'name' => 'required|string|max:200',
            'equipment_type' => 'required|string|max:100',
            'model' => 'nullable|string|max:100',
            'brand' => 'nullable|string|max:100',
            'capacity' => 'nullable|string|max:50',
            'current_location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        Equipment::create($validated);

        return redirect()->route('equipment.index')
            ->with('success', 'Equipo creado exitosamente.');
    }
}