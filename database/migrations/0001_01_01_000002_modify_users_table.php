<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->default(1)->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login')->nullable();
        });
    }
};