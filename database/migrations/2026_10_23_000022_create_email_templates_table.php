<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name');
            $table->string('subject');
            $table->longText('body');
            $table->text('description')->nullable();
            $table->json('variables')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        DB::table('email_templates')->insert([
            ['name'=>'welcome','display_name'=>'Welcome Email','subject'=>'Welcome to QTalent!','body'=>'<p>Hello {{name}},</p><p>Welcome to QTalent. We are excited to have you on board!</p>','description'=>'Sent when a new user registers','variables'=>json_encode(['name'=>'User\'s full name']),'is_active'=>true,'created_at'=>now(),'updated_at'=>now()],
            ['name'=>'job_approved','display_name'=>'Job Approved','subject'=>'Your job posting has been approved','body'=>'<p>Hello {{company_name}},</p><p>Your job posting "{{job_title}}" has been approved and is now live.</p>','description'=>'Sent when admin approves a job','variables'=>json_encode(['company_name'=>'Company name','job_title'=>'Job title']),'is_active'=>true,'created_at'=>now(),'updated_at'=>now()],
        ]);
    }
    public function down(): void { Schema::dropIfExists('email_templates'); }
};
