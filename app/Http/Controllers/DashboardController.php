<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Rental;
use App\Models\Client;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_equipment' => Equipment::where('is_active', true)->count(),
            'active_rentals' => Rental::where('status', 'activa')->count(),
            'total_clients' => Client::where('is_active', true)->count(),
            'pending_work_orders' => WorkOrder::where('status', 'pendiente')->count(),
            'available_equipment' => Equipment::where('status', 'disponible')->where('is_active', true)->count(),
            'rented_equipment' => Equipment::where('status', 'rentado')->where('is_active', true)->count(),
        ];

        return view('dashboard', compact('stats'));
    }

    public function adminDashboard(): View
    {
        if (!auth()->user()->hasPermission('all')) {
            abort(403, 'No tienes permisos de administrador');
        }

        $stats = [
            'total_equipment' => Equipment::count(),
            'active_rentals' => Rental::where('status', 'activa')->count(),
            'total_clients' => Client::count(),
            'pending_work_orders' => WorkOrder::where('status', 'pendiente')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function mapData(): \Illuminate\Http\JsonResponse
    {
        $equipment = Equipment::where('is_active', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'serial_number' => $item->serial_number,
                    'status' => $item->status,
                    'latitude' => $item->latitude,
                    'longitude' => $item->longitude,
                    'current_location' => $item->current_location,
                ];
            });

        return response()->json($equipment);
    }

    public function mapStats(): \Illuminate\Http\JsonResponse
    {
        $stats = [
            'total_generators' => Equipment::where('is_active', true)->count(),
            'covered_states' => Equipment::where('is_active', true)->distinct('current_location')->count(),
            'active_generators' => Equipment::where('status', 'rentado')->where('is_active', true)->count(),
            'inactive_generators' => Equipment::where('status', 'disponible')->where('is_active', true)->count(),
            'total_capacity' => (int) Equipment::where('is_active', true)->sum('capacity'),
        ];

        return response()->json($stats);
    }
}