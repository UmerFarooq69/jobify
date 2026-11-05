<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('candidate_applications', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->foreignId('candidate_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('job_requisition_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('pipeline_stage_id')->nullable();
            $table->foreign('pipeline_stage_id')->references('id')->on('pipeline_stages')->nullOnDelete();
            $table->unsignedBigInteger('resume_id')->nullable();
            $table->foreign('resume_id')->references('id')->on('resumes')->nullOnDelete();
            $table->string('status')->default('applied'); // applied/screening/interview/offer/rejected/withdrawn
            $table->string('source')->default('direct');
            $table->integer('match_score')->nullable();
            $table->json('match_report')->nullable();
            $table->json('screening_answers')->nullable();
            $table->text('note')->nullable();
            $table->timestamp('stage_entered_at')->nullable();
            $table->timestamp('applied_at')->useCurrent();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('candidate_applications'); }
};
