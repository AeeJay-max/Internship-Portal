<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('applicant_personal_info')) {
            Schema::create('applicant_personal_info', function (Blueprint $table) {
                $table->id();
                $table->foreignId('application_id')->nullable()->constrained('applications')->cascadeOnDelete();
                $table->string('first_name')->nullable();
                $table->string('middle_name')->nullable();
                $table->string('last_name')->nullable();
                $table->date('date_of_birth')->nullable();
                $table->string('gender')->nullable();
                $table->string('passport_number')->nullable();
                $table->string('national_id')->nullable();
                $table->string('country_of_birth')->nullable();
                $table->string('place_of_birth')->nullable();
                $table->string('resident_country')->nullable();
                $table->string('citizenship')->nullable();
                $table->string('nationality')->nullable();
                $table->string('marital_status')->nullable();
                $table->string('city')->nullable();
                $table->string('province')->nullable();
                $table->string('phone', 50)->nullable();
                $table->text('address')->nullable();
                $table->string('emergency_contact_name')->nullable();
                $table->string('emergency_contact_phone')->nullable();
                $table->string('armenian_language_proficiency')->nullable();
                $table->string('other_languages')->nullable();
                $table->string('ngo_membership')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('applicant_personal_info');
    }
};
