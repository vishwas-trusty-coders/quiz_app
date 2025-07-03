<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:15',
            'message' => 'required|string',
        ]);

        // Save the data to the database
        $contact = ContactMessage::create($validated);

        // Send an email to the admin
        Mail::send('emails.contact', ['contact' => $contact], function ($message) {
            $message->to('shilpi@tipstat.com') 
                    ->subject('New Contact Message');
        });

        // Redirect back with a success message
        return back()->with('success', 'Your message has been sent successfully!');
    }
}
