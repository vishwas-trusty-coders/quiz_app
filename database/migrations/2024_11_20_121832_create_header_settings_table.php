<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeaderSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('header_settings', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable(); // For the logo path
            $table->json('menu_items')->nullable(); // JSON field to store menu items
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('header_settings');
    }
}
