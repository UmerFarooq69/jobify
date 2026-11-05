<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('candidate_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('headline')->nullable();
            $table->text('summary')->nullable();
            $table->json('skills')->nullable();
            $table->json('experience')->nullable();
            $table->json('education')->nullable();
            $table->json('certifications')->nullable();
            $table->json('preferences')->nullable();
            $table->integer('completeness_score')->default(0);
            $table->boolean('onboarding_completed')->default(false);
            $table->string('identity_status')->default('none'); // none/pending/verified/rejected
            $table->string('identity_rejection_reason')->nullable();
            $table->string('identity_doc_front')->nullable();
            $table->string('identity_doc_back')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('candidate_profiles'); }
};
