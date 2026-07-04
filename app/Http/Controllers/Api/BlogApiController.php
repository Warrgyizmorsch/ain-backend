<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogApiController extends Controller
{
    /**
     * Get paginated list of blogs.
     */
    public function index(Request $request)
    {
        $limit = intval($request->query('limit', 10));
        // Clamp limit between 1 and 50 to prevent memory exhaustion attacks
        $limit = max(1, min(50, $limit));

        $query = Blog::latest();

        // Optional search filtering by title
        if ($request->has('search')) {
            $search = $request->query('search');
            // 'tittle' matches the typo in the db column 'tittle'
            $query->where('tittle', 'like', '%' . $search . '%');
        }

        // Optional filtering by type
        if ($request->has('type')) {
            $type = $request->query('type');
            $query->where('type', $type);
        }

        // Select columns to keep the response payload light
        $blogs = $query->paginate($limit, [
            'id', 'tittle', 'slug', 'images', 'type', 
            'meta_title', 'meta_discribtion', 'created_at'
        ]);

        $blogs->getCollection()->transform(function ($blog) {
            if (!empty($blog->images) && !str_starts_with($blog->images, 'http://') && !str_starts_with($blog->images, 'https://')) {
                $blog->images = url($blog->images);
            }
            return $blog;
        });

        return response()->json([
            'success' => true,
            'data' => $blogs
        ]);
    }

    /**
     * Get specific blog details by slug.
     */
    public function show($slug)
    {
        $slug = urldecode($slug);

        $blog = Blog::where('slug', $slug)->first();

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Blog not found'
            ], 404);
        }

        if (!empty($blog->images) && !str_starts_with($blog->images, 'http://') && !str_starts_with($blog->images, 'https://')) {
            $blog->images = url($blog->images);
        }

        return response()->json([
            'success' => true,
            'data' => $blog
        ]);
    }
}
