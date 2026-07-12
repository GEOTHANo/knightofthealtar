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
        Schema::create('attendance', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->date('meeting_week_start');
            $table->date('meeting_week_end');
            $table->date('attendance_date');
            $table->enum('status', ['Present', 'Late', 'Absent', 'Excused'])->default('Absent');
            $table->text('remarks')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('members')->cascadeOnDelete();
            $table->timestamp('date_recorded')->useCurrent();
            $table->timestamps();

            $table->unique(['member_id', 'meeting_week_start', 'attendance_date']);
            $table->index(['member_id', 'meeting_week_start']);
            $table->index('attendance_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};