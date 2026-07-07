<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectApiController extends Controller
{
    /**
     * Display a listing of subjects.
     * Supports:
     * - 'search' query parameter to search by name.
     * - 'active_only' query parameter to only return active subjects.
     * - Pagination if 'page' or 'limit' parameters are specified.
     */
    public function index(Request $request)
    {
        try {
            $query = Subject::query();

            // Active only filter
            if ($request->has('active_only') && $request->boolean('active_only')) {
                $query->where('is_active', true);
            }

            // Search filter
            if ($request->has('search') && !empty($request->query('search'))) {
                $search = $request->query('search');
                $query->where('name', 'like', '%' . $search . '%');
            }

            // Order by name ascending
            $query->orderBy('name', 'asc');

            // Check if pagination is requested
            if ($request->has('page') || $request->has('limit')) {
                $limit = intval($request->query('limit', 15));
                $limit = max(1, min(100, $limit)); // restrict limit to a reasonable range
                $subjects = $query->paginate($limit);
            } else {
                $subjects = $query->get();
            }

            return response()->json([
                'success' => true,
                'data' => $subjects
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve subjects: ' . $e->getMessage()
            ], 500);
        }
    }
}
