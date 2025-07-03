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
        Schema::create('package_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key to the users table
            $table->foreignId('question_bank_id')->constrained()->onDelete('cascade'); // Foreign key to the question_banks table
            $table->string('question_bank_name')->nullable();
            $table->json('subject')->nullable();
            $table->json('topic')->nullable(); 
            $table->integer('credits_spent')->nullable(); // Credits spent on the purchase
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_purchases');
    }
};
