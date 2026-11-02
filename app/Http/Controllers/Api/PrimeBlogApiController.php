<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PrimeBlog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PrimeBlogApiController extends Controller
{
    public function __construct()
    {
        \App\Services\PrimeAssignmentSetupService::ensureSetup();
    }

    /**
     * Get paginated list of Prime Blogs only (Completely isolated).
     */
    public function index(Request $request)
    {
        $limit = intval($request->query('limit', 10));
        $limit = max(1, min(50, $limit));

        $query = PrimeBlog::latest('id');

        // Optional search filtering by title or slug
        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Optional category / type filtering
        if ($request->filled('category')) {
            $query->where('category', $request->query('category'));
        } elseif ($request->filled('type')) {
            $query->where('category', $request->query('type'));
        }

        $blogs = $query->paginate($limit, [
            'id', 'title', 'slug', 'image', 'category',
            'author_name', 'meta_title', 'meta_description', 'created_at',
        ]);

        $blogs->getCollection()->transform(function ($blog) {
            $imgUrl = $blog->image_url ?: (empty($blog->image) ? null : url(ltrim($blog->image, '/')));
            $blog->image = $imgUrl;

            // Legacy-compatible aliases for Next.js frontend
            $blog->tittle           = $blog->title;
            $blog->images           = $imgUrl;
            $blog->meta_discribtion = $blog->meta_description;
            $blog->type             = $blog->category;

            return $blog;
        });

        return response()->json([
            'success' => true,
            'data'    => $blogs,
        ]);
    }

    /**
     * Get prime blog details by slug (Strictly Prime Blogs table).
     */
    public function show($slug)
    {
        $slug = urldecode($slug);
        $cleanSlug = trim($slug, '/');

        $blog = PrimeBlog::where(function ($query) use ($slug, $cleanSlug) {
            $query->where('slug', $cleanSlug)
                  ->orWhere('slug', '/' . $cleanSlug)
                  ->orWhere('slug', $slug)
                  ->orWhere('slug', 'like', '%/' . $cleanSlug)
                  ->orWhere('slug', 'like', '%' . $cleanSlug . '%');
            if (is_numeric($cleanSlug)) {
                $query->orWhere('id', (int) $cleanSlug);
            }
        })->first();

        // If not found by slug, try title
        if (!$blog) {
            $blog = PrimeBlog::where('title', 'like', '%' . $cleanSlug . '%')->first();
        }

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => "Prime blog not found for '{$slug}'",
            ], 404);
        }

        $imgUrl = $blog->image_url ?: (empty($blog->image) ? null : url(ltrim($blog->image, '/')));
        $blog->image = $imgUrl;

        // Legacy-compatible aliases
        $blog->tittle           = $blog->title;
        $blog->images           = $imgUrl;
        $blog->meta_discribtion = $blog->meta_description;
        $blog->type             = $blog->category;

        // Attach author object
        if (!empty($blog->author_id) && Schema::hasTable('author')) {
            $author = DB::table('author')->where('id', $blog->author_id)->first();
            if ($author) {
                $blog->author = (object)[
                    'id'          => $author->id,
                    'name'        => $author->name,
                    'photo'       => !empty($author->photo) ? url(ltrim($author->photo, '/')) : null,
                    'description' => $author->description ?? 'Academic Writer',
                ];
            } else {
                $blog->author = (object)[
                    'name' => $blog->author_name ?: 'Prime Team',
                ];
            }
        } else {
            $blog->author = (object)[
                'name' => $blog->author_name ?: 'Prime Team',
            ];
        }

        return response()->json([
            'success' => true,
            'data'    => $blog,
        ]);
    }

    /**
     * Get list of unique prime blog categories.
     */
    public function categories()
    {
        $categories = PrimeBlog::whereNotNull('category')
            ->where('category', '!=', '')
            ->selectRaw('category, COUNT(*) as blog_count')
            ->groupBy('category')
            ->orderBy('category', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $categories,
        ]);
    }
}
