<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mass_weekdays', function (Blueprint $table): void {
            $table->id();
            $table->enum('day', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']);
            $table->enum('mass_type', ['AM', 'PM']);
            $table->time('mass_time');
            $table->string('description')->nullable();
            $table->enum('status', ['Active', 'Archived'])->default('Active');
            $table->timestamps();
        });

        Schema::create('mass_sundays', function (Blueprint $table): void {
            $table->id();
            $table->string('mass_name')->default('Sunday Mass');
            $table->time('mass_time');
            $table->string('description')->nullable();
            $table->enum('status', ['Active', 'Archived'])->default('Active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mass_sundays');
        Schema::dropIfExists('mass_weekdays');
    }
};
