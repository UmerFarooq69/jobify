<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('job_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_profile_id')->constrained()->cascadeOnDelete();
            $table->string('keywords')->nullable();
            $table->string('employment_type')->nullable();
            $table->string('location')->nullable();
            $table->string('frequency')->default('daily'); // daily/weekly
            $table->integer('new_matches_count')->default(0);
            $table->timestamp('last_notified_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('job_alerts'); }
};
