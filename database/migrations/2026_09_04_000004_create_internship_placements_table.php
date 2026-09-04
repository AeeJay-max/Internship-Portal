<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internship_placements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->onDelete('cascade');
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->string('division_unit')->nullable();
            $table->string('placement_location')->nullable();
            $table->string('supervisor_name');
            $table->string('supervisor_email')->nullable();
            $table->string('supervisor_phone')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('duration')->nullable();
            $table->text('placement_notes')->nullable();
            $table->text('reporting_instructions')->nullable();
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internship_placements');
    }
};
