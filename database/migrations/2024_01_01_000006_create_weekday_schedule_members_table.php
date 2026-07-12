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
        Schema::create('weekday_schedule_members', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('weekday_schedule_id')->constrained('schedule_weekdays')->cascadeOnDelete();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->boolean('is_active')->default(true);
            $table->date('assigned_date');
            $table->timestamps();

            $table->unique(['weekday_schedule_id', 'member_id']);
            $table->index(['member_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekday_schedule_members');
    }
};