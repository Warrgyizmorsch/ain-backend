<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PrimeReview;
use App\Services\PrimeAssignmentSetupService;
use Illuminate\Http\Request;

class PrimeReviewApiController extends Controller
{
    public function __construct()
    {
        PrimeAssignmentSetupService::ensureSetup();
    }

    /**
     * GET /api/prime/reviews
     * Query: limit (1-50, default 10), page, search, service, location, rating
     */
    public function index(Request $request)
    {
        $limit = max(1, min(50, (int) $request->query('limit', 10)));

        $query = PrimeReview::where('is_active', true)->latest('id');

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        if ($request->filled('service')) {
            $query->where('services_type', $request->query('service'));
        }
        if ($request->filled('location')) {
            $query->where('location', $request->query('location'));
        }
        if ($request->filled('rating')) {
            $query->where('customer_rating', (int) $request->query('rating'));
        }

        $stats = (clone $query)->reorder()->toBase()
            ->selectRaw('COUNT(*) as total, AVG(customer_rating) as avg_rating')
            ->first();

        $reviews = $query->paginate($limit);
        $reviews->getCollection()->transform(fn ($review) => $this->format($review));

        return response()->json([
            'success'        => true,
            'average_rating' => $stats && $stats->total ? round((float) $stats->avg_rating, 1) : null,
            'total_reviews'  => (int) ($stats->total ?? 0),
            'data'           => $reviews,
        ]);
    }

    /**
     * GET /api/prime/reviews/{id}
     */
    public function show($id)
    {
        $review = ctype_digit((string) $id)
            ? PrimeReview::where('is_active', true)->find((int) $id)
            : null;

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Prime review not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $this->format($review),
        ]);
    }

    private function format(PrimeReview $review): array
    {
        return [
            'id'              => $review->id,
            'name'            => $review->name,
            'deadline'        => $review->deadline,
            'submission_date' => optional($review->submission_date)->format('Y-m-d'),
            'service'         => $review->services_type,
            'location'        => $review->location,
            'rating'          => (int) $review->customer_rating,
            'description'     => $review->description,
            'review_reply'    => $review->review_reply,
            'created_at'      => $review->created_at,
        ];
    }
}
