<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function index(Request $request)
    {
        $query = Enquiry::query();

        // Search filter
        if ($request->has('search') && !empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('mobile', 'like', '%' . $search . '%')
                  ->orWhere('subject', 'like', '%' . $search . '%')
                  ->orWhere('inquiry_type', 'like', '%' . $search . '%')
                  ->orWhere('message', 'like', '%' . $search . '%');
            });
        }

        $enquiries = $query->latest()->paginate(15)->withQueryString();

        return view('enquiries.index', compact('enquiries'));
    }

    public function destroy(Enquiry $enquiry)
    {
        $enquiry->delete();
        return back()->with('success', 'Enquiry deleted successfully.');
    }
}
