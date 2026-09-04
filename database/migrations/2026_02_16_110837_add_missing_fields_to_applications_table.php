<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('applications', function (Blueprint $table) {
        $table->string('high_school_name')->nullable();
        $table->integer('graduation_year')->nullable();
        $table->string('gpa')->nullable();
        $table->unsignedBigInteger('program_id')->nullable();
        $table->string('passport_path')->nullable();
        $table->string('transcript_path')->nullable();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            //
        });
    }
};
