<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('equipment_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['preventivo', 'correctivo']);
            $table->enum('priority', ['baja', 'media', 'alta'])->default('media');
            $table->text('description');
            $table->enum('status', ['pendiente', 'en_proceso', 'completada', 'cancelada'])->default('pendiente');
            $table->date('scheduled_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->decimal('estimated_hours', 5, 2)->nullable();
            $table->decimal('actual_hours', 5, 2)->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }
};