<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('otps', function (Blueprint $table) {
            $table->id();
            $table->string('identifier');
            $table->string('type')->default('email'); // email/phone
            $table->string('otp');
            $table->string('role')->default('candidate');
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('otps'); }
};
