<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\ContactFormSubmitted;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function submitContactForm(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'subject' => 'required|string',
        ]);

        // Process the contact form data here (e.g., save to database, send email)
        $submission = new \App\Models\Contact();
        $submission->name = $request->name;
        $submission->email = $request->email;
        $submission->subject = $request->subject;
        $submission->message = $request->message;
        $submission->save();
        // Send email notification
        Mail::to('shilpi@trustycoders.com')->send(new ContactFormSubmitted($request->all()));

        return redirect()->back()->with('success', 'Thank you for contacting us! We will get back to you soon.');
    }
}
