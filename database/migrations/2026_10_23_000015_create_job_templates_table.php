<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('job_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->json('requirements')->nullable();
            $table->unsignedInteger('salary_min')->nullable();
            $table->unsignedInteger('salary_max')->nullable();
            $table->string('currency')->default('QAR');
            $table->string('location')->nullable();
            $table->string('employment_type')->nullable();
            $table->boolean('visa_sponsorship')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('job_templates'); }
};
