<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PrimeExpert;
use App\Services\PrimeAssignmentSetupService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PrimeExpertApiController extends Controller
{
    public function __construct()
    {
        PrimeAssignmentSetupService::ensureSetup();
    }

    /**
     * GET /api/prime/experts  (alias: /api/prime/writers)
     * Query: limit (1-50, default 10), page, search, subject, service, location
     */
    public function index(Request $request)
    {
        $limit = max(1, min(50, (int) $request->query('limit', 10)));

        $query = PrimeExpert::where('is_active', true)->latest('id');

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('service', 'like', "%{$search}%");
            });
        }

        foreach (['subject', 'service', 'location'] as $field) {
            if ($request->filled($field)) {
                $query->where($field, $request->query($field));
            }
        }

        $experts = $query->paginate($limit);
        $experts->getCollection()->transform(fn ($expert) => $this->format($expert, false));

        return response()->json([
            'success' => true,
            'data'    => $experts,
        ]);
    }

    /**
     * GET /api/prime/experts/{idOrSlug}  (alias: /api/prime/writers/{idOrSlug})
     */
    public function show($idOrSlug)
    {
        $key = trim(urldecode($idOrSlug), '/');

        $expert = PrimeExpert::where('is_active', true)
            ->where(function ($q) use ($key) {
                $q->where('slug', $key);
                if (ctype_digit($key)) {
                    $q->orWhere('id', (int) $key);
                }
            })
            ->first();

        if (!$expert) {
            return response()->json([
                'success' => false,
                'message' => 'Prime expert not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $this->format($expert, true),
        ]);
    }

    private function format(PrimeExpert $expert, bool $detail): array
    {
        $reviews = is_array($expert->customer_review) ? $expert->customer_review : [];
        $ratings = array_values(array_filter(array_map('intval', array_column($reviews, 'rating'))));

        $data = [
            'id'                => $expert->id,
            'name'              => $expert->name,
            'slug'              => $expert->slug,
            'image'             => $expert->image ? url(ltrim($expert->image, '/')) : null,
            'image_alt'         => $expert->image_alt,
            'gender'            => $expert->gender,
            'degree'            => $expert->degree,
            'experience'        => $expert->experience,
            'rating'            => $expert->rating,
            'subject'           => $expert->subject,
            'service'           => $expert->service,
            'location'          => $expert->location,
            'finish_order'      => $expert->finish_order,
            'inprogress_order'  => $expert->inprogress_order,
            'success_rate'      => $expert->success,
            'average_rating'    => $ratings ? round(array_sum($ratings) / count($ratings), 1) : $expert->rating,
            'total_reviews'     => count($reviews),
            'short_description' => $expert->content ? Str::limit(trim(strip_tags($expert->content)), 200) : null,
            'skills'            => $expert->skills ?: [],
        ];

        if ($detail) {
            $data += [
                'content'          => $expert->content,
                'helpus'           => $expert->helpus ?: [],
                'customer_reviews' => $reviews,
                'meta_title'       => $expert->meta_tag,
                'meta_description' => $expert->meta_description,
                'created_at'       => $expert->created_at,
                'updated_at'       => $expert->updated_at,
            ];
        }

        return $data;
    }
}
