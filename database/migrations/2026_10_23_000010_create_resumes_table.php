<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('resumes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_profile_id')->constrained()->cascadeOnDelete();
            $table->string('file_path')->nullable();
            $table->string('original_name')->nullable();
            $table->json('parsed_data')->nullable();
            $table->integer('version')->default(1);
            $table->boolean('is_ai_generated')->default(false);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('resumes'); }
};
