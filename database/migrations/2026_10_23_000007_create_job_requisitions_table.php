<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('job_requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->foreign('department_id')->references('id')->on('departments')->nullOnDelete();
            $table->unsignedBigInteger('job_type_id')->nullable();
            $table->foreign('job_type_id')->references('id')->on('job_taxonomies')->nullOnDelete();
            $table->unsignedBigInteger('job_function_id')->nullable();
            $table->foreign('job_function_id')->references('id')->on('job_taxonomies')->nullOnDelete();
            $table->string('title');
            $table->text('summary')->nullable();
            $table->longText('description')->nullable();
            $table->longText('requirements')->nullable();
            $table->json('skills')->nullable();
            $table->json('screening_questions')->nullable();
            $table->unsignedInteger('salary_min')->nullable();
            $table->unsignedInteger('salary_max')->nullable();
            $table->string('currency')->default('QAR');
            $table->string('location')->nullable();
            $table->string('employment_type')->default('full_time'); // full_time/part_time/contract/freelance/internship
            $table->boolean('visa_sponsorship')->default(false);
            $table->boolean('local_hire')->default(false);
            $table->boolean('urgent_hire')->default(false);
            $table->boolean('is_confidential')->default(false);
            $table->string('status')->default('draft'); // draft/active/paused/closed/archived
            $table->string('moderation_status')->default('pending'); // pending/approved/flagged/removed
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->fullText(['title','location']);
        });
    }
    public function down(): void { Schema::dropIfExists('job_requisitions'); }
};
