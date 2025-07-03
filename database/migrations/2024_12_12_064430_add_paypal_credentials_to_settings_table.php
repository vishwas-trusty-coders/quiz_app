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
        Schema::table('settings', function (Blueprint $table) {
            // PayPal credentials
            $table->string('paypal_sandbox_client_id')->nullable();
            $table->string('paypal_sandbox_client_secret')->nullable();
            $table->string('paypal_production_client_id')->nullable();
            $table->string('paypal_production_client_secret')->nullable();

            // PayPal environment
            $table->enum('paypal_environment', ['sandbox', 'production'])->default('sandbox');
            $table->dropColumn('payment_info');
            $table->dropColumn('secret_key');
            $table->dropColumn('secret_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'paypal_sandbox_client_id',
                'paypal_sandbox_client_secret',
                'paypal_production_client_id',
                'paypal_production_client_secret',
                'paypal_environment',
            ]);
        });
    }
};
