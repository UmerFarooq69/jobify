<?php

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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('payment_uuid')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('number',15);
            $table->enum('payment_method', ['JazzCash', 'EasyPaisa', 'Bank', 'GooglePay', 'Paypal', 'CreditCard/DebitCard']);
            $table->enum('plan', ['Monthly', 'Yearly'])->default('Monthly');
            $table->string('attachment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
