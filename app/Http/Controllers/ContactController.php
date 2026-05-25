<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'mobile' => 'required|string|max:15',
            'message' => 'required|string',
        ]);
    
        try {
            
            Mail::to('order@assignnmentinneed.com')->send(new ContactMail($validated));
            return redirect()->back()->with('success', 'Thank you! Your message has been sent successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Oops! Something went wrong. Please try again later.');
        }
    }
}