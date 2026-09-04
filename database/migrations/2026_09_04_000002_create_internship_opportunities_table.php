<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internship_opportunities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->integer('positions_count')->default(1);
            $table->date('opening_date')->nullable();
            $table->date('closing_date')->nullable();
            $table->integer('duration_months')->default(6);
            $table->date('start_date')->nullable();
            $table->string('status')->default('open'); // draft, open, closed, filled, archived
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internship_opportunities');
    }
};
