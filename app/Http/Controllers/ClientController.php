<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(): View
    {
        if (!auth()->user()->hasPermission('read')) {
            abort(403, 'No tienes permisos para ver clientes');
        }
        
        $clients = Client::where('is_active', true)->orderBy('name')->get();
        return view('clients.index', compact('clients'));
    }

    public function show(Client $client): View
    {
        if (!auth()->user()->hasPermission('read')) {
            abort(403, 'No tienes permisos para ver clientes');
        }

        $client->load(['rentals' => function($query) {
            $query->orderBy('created_at', 'desc');
        }, 'rentals.equipment']);

        return view('clients.show', compact('client'));
    }

    public function create(): View
    {
        if (!auth()->user()->hasPermission('write')) {
            abort(403, 'No tienes permisos para crear clientes');
        }
        
        return view('clients.create');
    }

    public function store(Request $request): RedirectResponse
    {
        if (!auth()->user()->hasPermission('write')) {
            abort(403, 'No tienes permisos para crear clientes');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'rfc' => 'nullable|string|max:20',
            'contact_name' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:150',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        Client::create($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Cliente creado exitosamente.');
    }
}