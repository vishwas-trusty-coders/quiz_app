<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Settings;
use Config;

class PayPalConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Retrieve PayPal settings from the Settings table
        $settings = Settings::first(); // Assuming only one row of settings
        
        if ($settings) {
            // Set PayPal configuration based on the retrieved settings
            $paypalSettings = [
                'mode'    => $settings->paypal_environment, // 'sandbox' or 'live'
                'sandbox' => [
                    'client_id'     => $settings->paypal_sandbox_client_id,
                    'client_secret' => $settings->paypal_sandbox_client_secret,
                    'app_id'        => '',
                ],
                'production' => [
                    'client_id'     => $settings->paypal_production_client_id,
                    'client_secret' => $settings->paypal_production_client_secret,
                    'app_id'        => '',
                ],
                'payment_action' => 'Sale', // Can be 'Sale', 'Authorization', or 'Order'
                'currency'       => 'USD',
                'notify_url'     => '',  // Optional, your webhook URL
                'locale'         => 'en_US',
                'validate_ssl'   => true, // Set to true in production
            ];

            // Update the PayPal config with the values from the database
            Config::set('paypal', $paypalSettings);
        }
    }
}
