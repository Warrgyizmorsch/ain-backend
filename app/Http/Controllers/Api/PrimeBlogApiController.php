<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PrimeBlog;
use Illuminate\Http\Request;

class PrimeBlogApiController extends Controller
{
    /**
     * Get paginated list of published prime blogs.
     */
    public function index(Request $request)
    {
        $limit = intval($request->query('limit', 10));
        $limit = max(1, min(50, $limit));

        $query = PrimeBlog::where('is_published', true)->latest('id');

        // Optional search filtering by title or slug
        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Optional category filtering
        if ($request->filled('category')) {
            $query->where('category', $request->query('category'));
        }

        $blogs = $query->paginate($limit, [
            'id', 'title', 'slug', 'image', 'category',
            'author_name', 'meta_title', 'meta_description', 'created_at',
        ]);

        $blogs->getCollection()->transform(function ($blog) {
            $blog->image = $blog->image_url;
            return $blog;
        });

        return response()->json([
            'success' => true,
            'data'    => $blogs,
        ]);
    }

    /**
     * Get prime blog details by slug.
     */
    public function show($slug)
    {
        $slug = urldecode($slug);

        $blog = PrimeBlog::where('slug', $slug)
            ->where('is_published', true)
            ->first();

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Prime blog not found',
            ], 404);
        }

        $blog->image = $blog->image_url;

        return response()->json([
            'success' => true,
            'data'    => $blog,
        ]);
    }

    /**
     * Get list of unique blog categories.
     */
    public function categories()
    {
        $categories = PrimeBlog::where('is_published', true)
            ->whereNotNull('category')
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

