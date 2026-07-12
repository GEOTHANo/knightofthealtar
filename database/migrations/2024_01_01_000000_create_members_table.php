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
        Schema::create('members', function (Blueprint $table): void {
            $table->id();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('profile_picture')->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Prefer not to say']);
            $table->string('complete_address')->nullable();
            $table->string('contact_number', 25)->nullable();
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
            $table->enum('status', ['Active', 'Inactive', 'Alumni'])->default('Active');
            $table->boolean('is_deleted')->default(false);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('batch_year');
            $table->index('gkk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};