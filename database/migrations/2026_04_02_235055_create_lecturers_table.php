<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lecturers', function (Blueprint $table) {
            $table->id();
            $table->string('name');                    // e.g. "Dr. Armen Petrosyan"
            $table->string('title')->default('Dr.');   // Dr. / Prof. / Assoc. Prof.
            $table->string('email')->unique();
            $table->string('faculty')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lecturers');
    }
};
