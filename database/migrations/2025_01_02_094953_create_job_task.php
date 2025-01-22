<?php

use App\Models\Company;
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
        Schema::create('job_task', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Company::class, 'company_id')->nullable()->constrained('companies')
            ->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('job_title');
            $table->string('description');
            $table->decimal('job_salary');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_task');
    }
};
