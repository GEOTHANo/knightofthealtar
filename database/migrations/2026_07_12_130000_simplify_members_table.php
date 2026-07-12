<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop emergency_contacts table first (it has FK to members)
        Schema::dropIfExists('emergency_contacts');

        // Drop columns one by one only if they exist (safe for both local & online DB)
        $columnsToDrop = [
            'profile_picture',
            'gender',
            'complete_address',
            'email_address',
            'school_attended',
            'mother_name',
            'mother_occupation',
            'father_name',
            'father_occupation',
            'number_of_siblings',
            'gkk',
            'date_of_acceptance',
            'batch_year',
            'date_added',
            'is_deleted',
        ];

        Schema::table('members', function (Blueprint $table) use ($columnsToDrop): void {
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('members', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table): void {
            $table->string('profile_picture')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Prefer not to say'])->default('Male');
            $table->string('complete_address')->nullable();
            $table->string('email_address')->unique()->nullable();
            $table->string('school_attended')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('father_name')->nullable();
            $table->string('father_occupation')->nullable();
            $table->integer('number_of_siblings')->default(0);
            $table->string('gkk')->nullable();
            $table->date('date_of_acceptance')->nullable();
            $table->year('batch_year')->nullable();
            $table->timestamp('date_added')->useCurrent();
            $table->boolean('is_deleted')->default(false);
        });

        Schema::create('emergency_contacts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->string('contact_name');
            $table->string('relationship');
            $table->string('address')->nullable();
            $table->string('contact_number', 25);
            $table->timestamps();
            $table->index('member_id');
        });
    }
};
