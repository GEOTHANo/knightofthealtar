<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedule_weekdays', function (Blueprint $table): void {
            $table->enum('status', ['not checked', 'checked'])->default('not checked')->after('mass_time');
        });

        Schema::table('schedule_sunday', function (Blueprint $table): void {
            $table->enum('status', ['not checked', 'checked'])->default('not checked')->after('notes');
        });

        // Add attendance_status to schedule member pivot tables
        Schema::table('weekday_schedule_members', function (Blueprint $table): void {
            $table->enum('attendance_status', ['Present', 'Absent', 'Excused'])->nullable()->after('assigned_date');
        });

        Schema::table('sunday_schedule_members', function (Blueprint $table): void {
            $table->enum('attendance_status', ['Present', 'Absent', 'Excused'])->nullable()->after('assigned_date');
        });
    }

    public function down(): void
    {
        Schema::table('schedule_weekdays', function (Blueprint $table): void {
            $table->dropColumn('status');
        });

        Schema::table('schedule_sunday', function (Blueprint $table): void {
            $table->dropColumn('status');
        });

        Schema::table('weekday_schedule_members', function (Blueprint $table): void {
            $table->dropColumn('attendance_status');
        });

        Schema::table('sunday_schedule_members', function (Blueprint $table): void {
            $table->dropColumn('attendance_status');
        });
    }
};
