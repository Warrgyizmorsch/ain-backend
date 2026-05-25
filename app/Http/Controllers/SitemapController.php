<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sample;
use App\Models\SampleCategory;
use Illuminate\Http\Request;
use App\Models\Blog;

class SitemapController extends Controller
{
    public function blogSitemap()
    {
        $blogs = Blog::where('type', 'blog')
                     ->select('created_at', 'images', 'slug', 'tittle')
                     ->get();
    
        return response()
            ->view('sitemap.blogSitemap', ['blogs' => $blogs])
            ->header('Content-Type', 'text/xml');
    }


    public function freeSampleSitemap()
    {
        $sample = Sample::with('categotyData')
                ->get();
        $categories = SampleCategory::with('Sample') 
                ->has('Sample') 
                ->get();


        return response()
        ->view('sample.sitemap', ['sample' => $sample, 'categories' => $categories])
        ->header('Content-type', 'Text/xml');
    }
    
    
}
