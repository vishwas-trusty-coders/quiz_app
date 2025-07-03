<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_PeopleService;
use Google_Service_PeopleService_Person;
use Google_Service_PeopleService_EmailAddress;
use App\Models\NewsletterSubscription;

class GoogleContactsController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setAuthConfig(base_path('google-credentials.json'));
        $this->client->addScope(\Google_Service_PeopleService::CONTACTS);
        $this->client->setAccessType('offline');
        $this->client->setRedirectUri('https://ezmedicine.tipstat.com/callback');
    }

    public function store(Request $request)
    {
        $authUrl = $this->client->createAuthUrl();
        return redirect($authUrl); // Redirect to Google for authentication
    }

    public function handleCallback(Request $request)
    {
        // Exchange authorization code for access token
        $this->client->authenticate($request->get('code'));
        $accessToken = $this->client->getAccessToken();

        // Store the access token securely
        session(['google_access_token' => $accessToken]);

        return redirect()->route('homepage')->with('success', 'Google authentication successful!');
    }

    public function addToContacts(Request $request)
    {
        // Validate the request
        $request->validate(['email' => 'required|email']);

       // Set access token
       $accessToken = session('google_access_token');
       if (!$accessToken) {
           return redirect()->route('save-to-google-group')->with('error', 'Google access token is missing. Please authenticate again.');
       }

       $this->client->setAccessToken($accessToken);

       // Refresh the token if expired
       if ($this->client->isAccessTokenExpired()) {
           $this->client->refreshToken($this->client->getRefreshToken());
           session(['google_access_token' => $this->client->getAccessToken()]);
       }


        // Initialize Google People API
        $service = new Google_Service_PeopleService($this->client);

        // Create a new contact in Google Contacts
        $person = new Google_Service_PeopleService_Person();
        $emailAddress = new Google_Service_PeopleService_EmailAddress();
        $emailAddress->setValue($request->email);
        $person->setEmailAddresses([$emailAddress]);

        try {
            $createdContact = $service->people->createContact($person);

            // Add to a specific contact group
            // $contactGroupName = 'contactGroups/YOUR-GROUP-ID'; // Replace with your group ID
            // $service->contactGroups->members->modify($contactGroupName, [
            //     'resourceNamesToAdd' => [$createdContact->getResourceName()],
            // ]);

            // Save the contact to the custom table
            NewsletterSubscription::create([
                'email' => $request->email,
                'google_resource_name' => $createdContact->getResourceName(), 
                'subscribed_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Email added to Google Contacts and saved to the database successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to add email to Google Contacts: ' . $e->getMessage());
        }
    }

}
