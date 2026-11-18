<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('candidate_applications', function (Blueprint $table) {
            $table->unsignedTinyInteger('screening_rating')->nullable()->after('note');
        });
    }

    public function down(): void {
        Schema::table('candidate_applications', function (Blueprint $table) {
            $table->dropColumn('screening_rating');
        });
    }
};
