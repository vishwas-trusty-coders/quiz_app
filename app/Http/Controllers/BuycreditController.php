<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Facades\Auth;
use App\Models\Settings;

class BuycreditController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $setting = Settings::first();
        $creditPlans = $setting->credit_info;
        $pricePerCredit = 0;
        foreach($creditPlans as $creditPlan){
            if($creditPlan['number_of_credit'] == 1){
                $pricePerCredit = $creditPlan['price_of_credit'];
            }
        }
        return view('buy_credit', compact('user', 'creditPlans', 'pricePerCredit'));
    }

    private function getPayPalCredentials()
    {
        $settings = Settings::first(); // Assuming only one row of settings
        
        if (!$settings) {
            throw new \Exception('PayPal settings not configured in the database.');
        }
    
        $paypalSettings = [
            'mode'    => $settings->paypal_environment ?? 'sandbox',
            'payment_action' => 'Sale',
            'currency'       => $settings->currency ?? 'USD',
            'notify_url'     => '',  // Optional, your webhook URL
            'locale'         => 'en_US',
            'validate_ssl'   => true,
            'sandbox' => [
                'client_id'     => $settings->paypal_sandbox_client_id ?? '',
                'client_secret' => $settings->paypal_sandbox_client_secret ?? '',
                'app_id'        => '',
            ],
            'live' => [
                'client_id'     => $settings->paypal_production_client_id ?? '',
                'client_secret' => $settings->paypal_production_client_secret ?? '',
                'app_id'        => '',
            ],
        ];
    
        // Debug the settings to ensure everything is correct
        \Log::info('PayPal Config:', $paypalSettings);
    
        return $paypalSettings;
    }

    public function payment(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials($this->getPayPalCredentials());
        
        $paypalToken = $provider->getAccessToken();

        $amount = $request->input('amount');
        $credits = $request->input('credit_plan');

        // Store credits in the session to update after success
        session(['credits_to_add' => $credits]);

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('buy.credit.success'),
                "cancel_url" => route('buy.credit.cancel'),
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $amount,
                    ],
                ],
            ],
        ]);

        if (isset($response['id']) && $response['id'] != null) {
            foreach ($response['links'] as $link) {
                if ($link['rel'] == 'approve') {
                    return redirect()->away($link['href']);
                }
            }
        }

        return redirect()->route('buy_credit')->with('error', 'Unable to create PayPal transaction.');
    }

    public function success(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials($this->getPayPalCredentials());
        $provider->getAccessToken();

        $response = $provider->capturePaymentOrder($request->query('token'));

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            $user = Auth::user();
            $credits = session('credits_to_add');
            $user->credits += $credits; // Update user's credit balance
            $user->save();

            // Extract amount and currency
            $purchaseUnit = $response['purchase_units'][0];
            $capture = $purchaseUnit['payments']['captures'][0];
            $amount = $capture['amount']['value'];
            $currency = $capture['amount']['currency_code'];

            // Save order details in the database
            \App\Models\Order::create([
                'user_id' => $user->id,
                'paypal_order_id' => $response['id'],
                'amount' => $amount,
                'currency' => $currency,
                'status' => $response['status'],
                'credits' => $credits, // Save the credits purchased
                'response_data' => json_encode($response),
            ]);

            session()->forget('credits_to_add'); // Clear session
            return redirect()->route('buy_credit')->with('success', 'Transaction successful. Credits added!')->with('new_credits', $user->credits);
        }

        return redirect()->route('buy_credit')->with('error', 'Payment failed.');
    }

    public function cancel()
    {
        return redirect()->route('buy_credit')->with('error', 'You have canceled the transaction.');
    }
}
