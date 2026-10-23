<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $query = Blog::where('is_published', true)->latest();

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

        if (!$blog || !$blog->is_published) {
            return response()->json([
                'success' => false,
                'message' => 'Blog not found'
            ], 404);
        }

        if (!empty($blog->images) && !str_starts_with($blog->images, 'http://') && !str_starts_with($blog->images, 'https://')) {
            $blog->images = url($blog->images);
        }

        if (!empty($blog->content)) {
            // [MK - 2026-10-14] Auto-convert relative inline img src to absolute backend URLs for external frontend rendering
            $blog->content = normalize_blog_content_images($blog->content);
            $blog->content = render_blog_ctas($blog->content);
        }

        if (!empty($blog->author_id)) {
            $author = DB::table('author')
                ->where('id', $blog->author_id)
                ->select('id', 'name', 'photo', 'description')
                ->first();

            // Resilient fallback if author_id points directly to expert or user id
            if (!$author && \Illuminate\Support\Facades\Schema::hasTable('expert')) {
                $expert = DB::table('expert')->where('id', $blog->author_id)->first();
                if ($expert) {
                    $cleanDesc = trim(strip_tags($expert->content ?? ''));
                    $author = (object)[
                        'id' => $expert->id,
                        'name' => $expert->name,
                        'photo' => $expert->image,
                        'description' => mb_strlen($cleanDesc) > 500 ? mb_substr($cleanDesc, 0, 497) . '...' : $cleanDesc,
                    ];
                }
            }

            if (!$author && \Illuminate\Support\Facades\Schema::hasTable('users')) {
                $user = DB::table('users')->where('id', $blog->author_id)->first();
                if ($user) {
                    $author = (object)[
                        'id' => $user->id,
                        'name' => $user->name,
                        'photo' => $user->photo ?: 'assets/media/avatars/blank.png',
                        'description' => 'Academic Writer at Assignment in Need',
                    ];
                }
            }

            if ($author) {
                if (!empty($author->photo) && !str_starts_with($author->photo, 'http://') && !str_starts_with($author->photo, 'https://')) {
                    $author->photo = url(ltrim($author->photo, '/'));
                }
                $blog->author = $author;
            } else {
                $blog->author = null;
            }
        } else {
            $blog->author = null;
        }

        return response()->json([
            'success' => true,
            'data' => $blog
        ]);
    }
}
