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
        Schema::create('schedule_weekdays', function (Blueprint $table): void {
            $table->id();
            $table->date('week_start');
            $table->date('week_end');
            $table->enum('day', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']);
            $table->enum('mass_type', ['AM', 'PM']);
            $table->time('mass_time');
            $table->timestamps();

            $table->unique(['week_start', 'day', 'mass_type']);
            $table->index('week_start');
            $table->index('week_end');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_weekdays');
    }
};