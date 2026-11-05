<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pipeline_automations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_requisition_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('stage_id')->nullable();
            $table->foreign('stage_id')->references('id')->on('pipeline_stages')->nullOnDelete();
            $table->string('trigger_event'); // application_received/stage_moved/score_above/score_below
            $table->string('action_type'); // send_email/reject/move_stage/notify_team
            $table->json('action_config')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pipeline_automations'); }
};
