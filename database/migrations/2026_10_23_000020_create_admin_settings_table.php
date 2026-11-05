<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::create('admin_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general'); // general/ai/notifications/rate_limits
            $table->string('type')->default('string'); // string/boolean/integer/json
            $table->string('description')->nullable();
            $table->timestamps();
        });
        // Seed default settings
        DB::table('admin_settings')->insert([
            ['key'=>'site_name','value'=>'QTalent','group'=>'general','type'=>'string','description'=>'Platform name','created_at'=>now(),'updated_at'=>now()],
            ['key'=>'maintenance_mode','value'=>'false','group'=>'general','type'=>'boolean','description'=>'Put site in maintenance mode','created_at'=>now(),'updated_at'=>now()],
            ['key'=>'allow_guest_job_browse','value'=>'true','group'=>'general','type'=>'boolean','description'=>'Allow guests to browse jobs','created_at'=>now(),'updated_at'=>now()],
            ['key'=>'ai_match_threshold','value'=>'60','group'=>'ai','type'=>'integer','description'=>'Minimum match score to show job','created_at'=>now(),'updated_at'=>now()],
            ['key'=>'parser_base_url','value'=>'','group'=>'ai','type'=>'string','description'=>'LLM API base URL for parsing','created_at'=>now(),'updated_at'=>now()],
            ['key'=>'parser_api_key','value'=>'','group'=>'ai','type'=>'string','description'=>'LLM API key','created_at'=>now(),'updated_at'=>now()],
            ['key'=>'parser_model','value'=>'gemini-2.0-flash','group'=>'ai','type'=>'string','description'=>'LLM model name','created_at'=>now(),'updated_at'=>now()],
        ]);
    }
    public function down(): void { Schema::dropIfExists('admin_settings'); }
};
