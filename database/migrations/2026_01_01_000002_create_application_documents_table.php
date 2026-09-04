<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('application_documents')) {
            Schema::create('application_documents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('application_id')->constrained()->cascadeOnDelete();
                $table->foreignId('document_type_id')->nullable()->constrained()->nullOnDelete();
                $table->string('cert_name')->nullable();
                $table->string('file_path')->nullable();
                $table->boolean('is_verified')->default(false);
                $table->timestamp('uploaded_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('application_documents');
    }
};
