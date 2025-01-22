    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateApplicationsTable extends Migration
    {
        public function up()
        {
            Schema::create('applications', function (Blueprint $table) {
                $table->id();
                $table->string('applicant_name');
                $table->string('applicant_email');
                $table->string('cv')->nullable();
                $table->foreignId('job_id')->constrained('job_task')->onDelete('cascade');
                $table->timestamps();
            });
        }
        public function down()
        {
            Schema::dropIfExists('applications');
        }
    }

