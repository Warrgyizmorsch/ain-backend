<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewApiController extends Controller
{
    /**
     * Display a listing of reviews.
     * Supports:
     * - 'search' query parameter to search name, description or location.
     * - 'services_type' query parameter to filter by service type.
     * - 'customer_rating' query parameter to filter by rating.
     * - Pagination if 'page' or 'limit' parameters are specified.
     */
    public function index(Request $request)
    {
        try {
            $query = Review::query();

            // Search filter
            if ($request->has('search') && !empty($request->query('search'))) {
                $search = $request->query('search');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%')
                      ->orWhere('location', 'like', '%' . $search . '%');
                });
            }

            // Services Type filter
            if ($request->has('services_type') && !empty($request->query('services_type'))) {
                $query->where('services_type', $request->query('services_type'));
            }

            // Customer Rating filter
            if ($request->has('customer_rating') && !empty($request->query('customer_rating'))) {
                $query->where('customer_rating', $request->query('customer_rating'));
            }

            // Get latest reviews first
            $query->latest();

            // Check if pagination is requested
            if ($request->has('page') || $request->has('limit')) {
                $limit = intval($request->query('limit', 15));
                $limit = max(1, min(100, $limit)); // restrict limit to a reasonable range
                $reviews = $query->paginate($limit);
            } else {
                $reviews = $query->get();
            }

            return response()->json([
                'success' => true,
                'data' => $reviews
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve reviews: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified review.
     */
    public function show($id)
    {
        try {
            $review = Review::find($id);

            if (!$review) {
                return response()->json([
                    'success' => false,
                    'message' => 'Review not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $review
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
}
