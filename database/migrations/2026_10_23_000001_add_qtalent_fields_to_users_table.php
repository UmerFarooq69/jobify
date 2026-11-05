<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->string('uuid')->nullable()->unique()->after('id');
            $table->string('first_name')->nullable()->after('uuid');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('phone')->nullable()->after('email');
            $table->timestamp('phone_verified_at')->nullable()->after('phone');
            $table->string('role_type')->default('candidate')->after('role'); // candidate/company_admin/company_hiring_manager/company_recruiter/company_viewer/platform_admin
            $table->string('auth_provider')->default('local')->after('role_type'); // local/google/apple/linkedin/otp
            $table->string('status')->default('active')->after('auth_provider'); // active/suspended/deleted
            $table->boolean('identity_verified')->default(false)->after('status');
            $table->string('avatar')->nullable()->after('identity_verified');
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['uuid','first_name','last_name','phone','phone_verified_at','role_type','auth_provider','status','identity_verified','avatar']);
        });
    }
};
