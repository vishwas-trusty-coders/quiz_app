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
        Schema::create('ourservicespages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('service1_title')->nullable();
            $table->text('service1_description')->nullable();
            $table->string('service1_title2')->nullable();
            $table->json('service1_how_it_work')->nullable();
            $table->json('cta_button_service1')->nullable();
            $table->string('service1_image')->nullable();
            $table->text('service1_more_description')->nullable();
            $table->string('service2_title')->nullable();
            $table->text('service2_description')->nullable();
            $table->string('service2_title2')->nullable();
            $table->json('service2_how_it_work')->nullable();
            $table->json('cta_button_service2')->nullable();
            $table->string('service2_image')->nullable();
            $table->text('service2_more_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ourservicespages');
    }
};
