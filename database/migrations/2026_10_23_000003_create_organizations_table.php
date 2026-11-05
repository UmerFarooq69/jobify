<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type')->default('company'); // company/agency
            $table->string('entity_type')->nullable();
            $table->string('website')->nullable();
            $table->string('industry')->nullable();
            $table->string('size')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable()->default('QA');
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('cover')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->boolean('verified')->default(false);
            $table->string('moderation_status')->default('pending'); // pending/approved/flagged/removed
            $table->boolean('is_top_employer')->default(false);
            $table->string('status')->default('active');
            $table->string('subscription_tier')->default('free');
            $table->json('settings')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('organizations')->nullOnDelete();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('organizations'); }
};
