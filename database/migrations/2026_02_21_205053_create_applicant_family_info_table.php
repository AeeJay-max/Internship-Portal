<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applicant_family_info', function (Blueprint $table) {
            $table->id();

            $table->foreignId('application_id')
                ->constrained('applications')
                ->onDelete('cascade');

            $table->string('father_full_name')->nullable();
            $table->string('father_occupation')->nullable();

            $table->string('mother_full_name')->nullable();
            $table->string('mother_occupation')->nullable();

            $table->text('siblings')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applicant_family_info');
    }
};
