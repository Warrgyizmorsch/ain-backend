<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
class BlogController extends Controller
{
   public function loadMore(Request $request)
{
    $offset = intval($request->get('offset', 0));
    $limit = 8; // Number of blogs to load per request

    $blogs = Blog::latest()->skip($offset)->take($limit)->get();
    $totalBlogs = Blog::count();

    return response()->json([
        'html' => view('frontend.header.blog-cards', compact('blogs'))->render(),
        'hasMore' => $totalBlogs > ($offset + $limit),
    ]);
}




}
