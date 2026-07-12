<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schedule_sunday', function (Blueprint $table): void {
            $table->id();
            $table->date('mass_date')->unique();
            $table->string('mass_name')->default('Sunday Mass');
            $table->time('mass_time');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('mass_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_sunday');
    }
};