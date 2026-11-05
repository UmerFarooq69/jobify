<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('candidate_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('candidate_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('job_requisition_id')->constrained()->cascadeOnDelete();
            $table->text('notes')->nullable();
            $table->integer('rating')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('candidate_evaluations'); }
};
