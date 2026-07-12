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
        Schema::create('sunday_schedule_members', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('sunday_schedule_id')->constrained('schedule_sunday')->cascadeOnDelete();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->boolean('is_active')->default(true);
            $table->date('assigned_date');
            $table->timestamps();

            $table->unique(['sunday_schedule_id', 'member_id']);
            $table->index(['member_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sunday_schedule_members');
    }
};