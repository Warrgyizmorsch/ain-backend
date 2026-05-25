<?php

namespace App\Http\Controllers;

use App\Models\CareerApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

// app/Http/Controllers/CareerApplicationController.php

class CareerApplicationController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'current_location' => 'nullable|string|max:255',
            'experience_years' => 'nullable|numeric|min:0|max:50',
            'role_mode' => 'required|in:job,internship',
            'role_title' => 'nullable|string|max:255',

            'current_ctc' => 'nullable|string|max:255',
            'expected_ctc' => 'nullable|string|max:255',
            'notice_period' => 'nullable|string|max:255',

            'message' => 'nullable|string',
            'resume_url' => 'nullable|string|max:500',
            'portfolio_url' => 'nullable|string|max:500',

            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        // ✅ store file and set URL
        if ($request->hasFile('resume')) {
            $path = $request->file('resume')->store('resumes', 'public');
            $data['resume_url'] = Storage::url($path);
        }

        $data['source'] = $request->input('source', 'react-careers-page');
        $data['status'] = 'new';

        $application = CareerApplication::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Application submitted successfully',
            'id' => $application->id,
        ], 201);
    }

}

