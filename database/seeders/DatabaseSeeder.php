<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Equipment;
use App\Models\Rental;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Usuarios
        User::create([
            'name' => 'Administrador Principal',
            'email' => 'admin@resntas.com',
            'password' => Hash::make('password'),
            'role_id' => 1,
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Supervisor General', 
            'email' => 'supervisor@resntas.com',
            'password' => Hash::make('password'),
            'role_id' => 2,
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Operador Uno',
            'email' => 'operador@resntas.com', 
            'password' => Hash::make('password'),
            'role_id' => 3,
            'is_active' => true,
        ]);

        // Clientes
        $client1 = Client::create([
            'name' => 'Constructora Nacional SA de CV',
            'rfc' => 'CON800101ABC',
            'contact_name' => 'Ing. Roberto Martínez',
            'email' => 'r.martinez@constructoranacional.com',
            'phone' => '+52 55 1234 5678',
            'address' => 'Av. Insurgentes Sur 123, CDMX',
            'is_active' => true,
        ]);

        // Equipos
        $equipment1 = Equipment::create([
            'serial_number' => 'PL-001-2024',
            'name' => 'Generador Cummins 75KW',
            'equipment_type' => 'Planta de Luz',
            'model' => 'C75D6',
            'brand' => 'Cummins', 
            'capacity' => '75 KW',
            'status' => 'disponible',
            'current_location' => 'Almacén Central CDMX',
            'latitude' => 19.4326,
            'longitude' => -99.1332,
            'is_active' => true,
        ]);

        $equipment2 = Equipment::create([
            'serial_number' => 'PL-002-2024',
            'name' => 'Generador Caterpillar 100KW',
            'equipment_type' => 'Planta de Luz',
            'model' => 'C100',
            'brand' => 'Caterpillar',
            'capacity' => '100 KW', 
            'status' => 'disponible',
            'current_location' => 'Almacén Central CDMX',
            'latitude' => 19.4326,
            'longitude' => -99.1332,
            'is_active' => true,
        ]);

        // Rentas
        Rental::create([
            'rental_number' => 'RENT-20241110-0001',
            'client_id' => $client1->id,
            'equipment_id' => $equipment1->id,
            'start_date' => '2024-11-01',
            'end_date' => '2024-11-30',
            'total_days' => 30,
            'status' => 'activa',
            'total_amount' => 45000.00,
            'delivery_address' => 'Obra Lomas de Chapultepec, CDMX',
            'contact_phone' => '+52 55 1234 5678',
            'special_instructions' => 'Entregar en horario de 9am a 6pm',
            'created_by' => 1,
        ]);
    }
}