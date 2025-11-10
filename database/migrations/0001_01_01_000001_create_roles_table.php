<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->json('permissions')->nullable();
            $table->timestamps();
        });

        DB::table('roles')->insert([
            ['name' => 'admin', 'permissions' => json_encode(['all']), 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'supervisor', 'permissions' => json_encode(['read', 'write', 'reports']), 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'operador', 'permissions' => json_encode(['read', 'rentals']), 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
};