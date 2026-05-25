<?php

namespace App\Http\Controllers;

use App\Models\CareerApplication;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    // 
    public function index(Request $request)
    {
        $query = CareerApplication::query()->latest();

        // 🔎 Simple search (name, email, phone, role)

        

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('role_title', 'like', "%{$search}%");
            });
        }

        // 🎯 Filter by mode (job / internship)
        if ($roleMode = $request->get('role_mode')) {
            $query->where('role_mode', $roleMode);
        }

        // (Optional) Filter by status agar column hai
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }


         if ($request->filled('role')) {
            $query->where('role_title', 'LIKE', "%{$request->role}%");
        }

        $applications = $query->paginate(15);

        return view('back-end.career.index', compact('applications'));
    }
}
