<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Client;
use App\Models\Equipment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RentalController extends Controller
{
    public function index(): View
    {
        if (!auth()->user()->hasPermission('read')) {
            abort(403, 'No tienes permisos para ver rentas');
        }
        
        $rentals = Rental::with(['client', 'equipment', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('rentals.index', compact('rentals'));
    }

    public function show(Rental $rental): View
    {
        if (!auth()->user()->hasPermission('read')) {
            abort(403, 'No tienes permisos para ver rentas');
        }

        $rental->load(['client', 'equipment', 'creator']);
        return view('rentals.show', compact('rental'));
    }

    public function create(): View
    {
        if (!auth()->user()->hasPermission('write')) {
            abort(403, 'No tienes permisos para crear rentas');
        }
        
        $clients = Client::where('is_active', true)->orderBy('name')->get();
        $availableEquipment = Equipment::where('status', 'disponible')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('rentals.create', compact('clients', 'availableEquipment'));
    }

    public function store(Request $request): RedirectResponse
    {
        if (!auth()->user()->hasPermission('write')) {
            abort(403, 'No tienes permisos para crear rentas');
        }

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'equipment_id' => 'required|exists:equipment,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'delivery_address' => 'required|string',
            'contact_phone' => 'nullable|string|max:20',
            'special_instructions' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['total_days'] = \Carbon\Carbon::parse($validated['start_date'])
            ->diffInDays($validated['end_date']);

        Rental::create($validated);

        // Actualizar estado del equipo
        Equipment::find($validated['equipment_id'])->update(['status' => 'rentado']);

        return redirect()->route('rentals.index')
            ->with('success', 'Renta creada exitosamente.');
    }
}