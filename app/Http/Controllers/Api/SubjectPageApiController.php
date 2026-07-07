<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubjectPage;
use Illuminate\Http\Request;

class SubjectPageApiController extends Controller
{

    /**
     * Return full details for a published subject page by slug.
     * Includes associated experts and reviews.
     */
    public function show($slug)  
    {
        try {
            $slug = urldecode($slug);

            $page = SubjectPage::with('subject')
                ->where('slug', $slug)
                ->where('is_published', true)
                ->first();

            if (!$page) {
                return response()->json([
                    'success' => false,
                    'message' => 'Subject page not found',
                ], 404);
            }

            // Fetch related experts and reviews
            $experts = $page->selectedExperts()->map(function ($expert) {
                // Return absolute image URL
                if (!empty($expert->image) && !str_starts_with($expert->image, 'http')) {
                    $expert->image = request()->root() . '/' . ltrim($expert->image, '/');
                }
                return $expert;
            });

            $reviews = $page->selectedReviews();

            return response()->json([
                'success' => true,
                'data'    => [
                    'page'    => $page,
                    'experts' => $experts,
                    'reviews' => $reviews,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }
}
