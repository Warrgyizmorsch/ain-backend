<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServicePage;
use Illuminate\Http\Request;

class ServicePageApiController extends Controller
{
    /**
     * Get list of all published dynamic service pages.
     */
    public function index(Request $request)
    {
        $query = ServicePage::with('subject')
            ->where('is_published', true);

        // Filter by prefix query parameter if provided
        if ($request->has('prefix')) {
            $prefix = $request->query('prefix');
            $query->whereHas('subject', function ($q) use ($prefix) {
                $q->where('slug', $prefix)
                  ->orWhere('name', 'like', '%' . $prefix . '%');
            });
        }

        $pages = $query->latest()
            ->get(['id', 'subject_id', 'slug', 'meta_title', 'hero_heading']);

        return response()->json([
            'success' => true,
            'data' => $pages
        ]);
    }

    /**
     * Get list of published dynamic service pages by prefix slug or name.
     */
    public function getByPrefix($prefix)
    {
        $prefix = urldecode($prefix);

        $pages = ServicePage::with('subject')
            ->where('is_published', true)
            ->whereHas('subject', function ($q) use ($prefix) {
                $q->where('slug', $prefix)
                  ->orWhere('name', $prefix);
            })
            ->latest()
            ->get(['id', 'subject_id', 'slug', 'meta_title', 'hero_heading']);

        return response()->json([
            'success' => true,
            'data' => $pages
        ]);
    }

    /**
     * Get details of a specific dynamic service page by slug.
     */
    public function show($slug)
    {
        $slug = urldecode($slug);

        $page = ServicePage::with('subject')
            ->where('slug', $slug)
            ->where('is_published', true)
            ->first();

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Service page not found'
            ], 404);
        }

        // Fetch related experts and reviews using model methods
        $experts = $page->selectedExperts();
        $reviews = $page->selectedReviews();

        return response()->json([
            'success' => true,
            'data' => [
                'page' => $page,
                'experts' => $experts,
                'reviews' => $reviews
            ]
        ]);
    }
}
