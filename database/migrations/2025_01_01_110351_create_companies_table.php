    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    use App\Models\User;
    return new class extends Migration
    {
        /**
         * Run the migrations.
         */
        public function up(): void
        {
            Schema::create('companies', function (Blueprint $table) {
                $table->id();
                $table->foreignIdFor(User::class, 'user_id')->nullable()->constrained('users')
                ->cascadeOnDelete()->cascadeOnUpdate();
                $table->string('name');
                $table->string('city');
                $table->string('location');
                $table->string('image')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('companies');
        }
    };
