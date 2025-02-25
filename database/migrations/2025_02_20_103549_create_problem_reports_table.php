<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('problem_reports', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->enum('purpose', ['report_problem', 'report_job', 'report_company']);
            $table->enum('report_type', ['spam', 'fraud', 'harassment', 'misinformation', 'other']);
            $table->text('problem');
            $table->string('image')->nullable();
            $table->unsignedBigInteger('job_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('problem_reports');
    }
};
