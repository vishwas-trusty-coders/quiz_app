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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('admin_email')->nullable();
            $table->string('support_email')->nullable();
            $table->text('payment_info')->nullable();
            $table->text('secret_key')->nullable();
            $table->string('secret_url')->nullable();
            $table->string('currency')->nullable();
            $table->json('social_media_links')->nullable();
            $table->text('top_header_text')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
