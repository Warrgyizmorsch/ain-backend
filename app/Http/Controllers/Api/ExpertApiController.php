<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Experts;
use Illuminate\Http\Request;

class ExpertApiController extends Controller
{
    /**
     * Display a listing of experts.
     * Supports:
     * - 'search' query parameter to search by name, subject, service, location, or content.
     * - 'subject' query parameter to filter by subject.
     * - 'service' query parameter to filter by service.
     * - 'location' query parameter to filter by location.
     * - Pagination if 'page' or 'limit' parameters are specified.
     */
    public function index(Request $request)
    {
        try {
            $query = Experts::query();

            // Search filter
            if ($request->has('search') && !empty($request->query('search'))) {
                $search = $request->query('search');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('subject', 'like', '%' . $search . '%')
                      ->orWhere('service', 'like', '%' . $search . '%')
                      ->orWhere('location', 'like', '%' . $search . '%')
                      ->orWhere('content', 'like', '%' . $search . '%');
                });
            }

            // Subject filter
            if ($request->has('subject') && !empty($request->query('subject'))) {
                $query->where('subject', 'like', '%' . $request->query('subject') . '%');
            }

            // Service filter
            if ($request->has('service') && !empty($request->query('service'))) {
                $query->where('service', 'like', '%' . $request->query('service') . '%');
            }

            // Location filter
            if ($request->has('location') && !empty($request->query('location'))) {
                $query->where('location', 'like', '%' . $request->query('location') . '%');
            }

            // Order by latest created expert
            $query->latest();

            // Check if pagination is requested
            if ($request->has('page') || $request->has('limit')) {
                $limit = intval($request->query('limit', 15));
                $limit = max(1, min(100, $limit)); // restrict limit to a reasonable range
                $experts = $query->paginate($limit);
            } else {
                $experts = $query->get();
            }

            // Map and decode JSON arrays if they are stored as JSON string in DB
            $experts->transform(function ($expert) {
                return $this->formatExpertData($expert);
            });

            return response()->json([
                'success' => true,
                'data' => $experts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve experts: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified expert by ID or Slug.
     */
    public function show($idOrSlug)
    {
        try {
            $idOrSlug = urldecode($idOrSlug);

            if (is_numeric($idOrSlug)) {
                $expert = Experts::find($idOrSlug);
            } else {
                $expert = Experts::where('slug', $idOrSlug)->first();
            }

            if (!$expert) {
                return response()->json([
                    'success' => false,
                    'message' => 'Expert not found'
                ], 404);
            }

            $formattedExpert = $this->formatExpertData($expert);

            return response()->json([
                'success' => true,
                'data' => $formattedExpert
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper to safely decode arrays and format images.
     */
    private function formatExpertData($expert)
    {
        // Decode customer review if stored as a JSON string
        if (is_string($expert->customer_review)) {
            $expert->customer_review = json_decode($expert->customer_review, true);
        }
        if (is_string($expert->skills)) {
            $expert->skills = json_decode($expert->skills, true);
        }
        if (is_string($expert->helpus)) {
            $expert->helpus = json_decode($expert->helpus, true);
        }

        // If image is empty, use the default blank avatar
        if (empty($expert->image)) {
            $expert->image = 'assets/media/avatars/blank.png';
        }

        // Generate full image URL if not empty and doesn't start with http/https
        if (!empty($expert->image) && !str_starts_with($expert->image, 'http://') && !str_starts_with($expert->image, 'https://')) {
            $expert->image = request()->root() . '/' . ltrim($expert->image, '/');
        }

        // Set 'images' key (plural) for compatibility with frontend code expecting plural key
        $expert->setAttribute('images', $expert->image);

        return $expert;
    }
}
