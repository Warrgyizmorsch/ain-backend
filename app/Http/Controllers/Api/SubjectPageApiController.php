<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SubjectPageController;
use App\Models\SubjectPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubjectPageApiController extends Controller
{
    /**
     * Get list of all published dynamic subject pages.
     */
    public function index(Request $request)
    {
        try {
            SubjectPageController::ensureCategoryColumn();

            $query = SubjectPage::with('subject')
                ->whereHas('subject')
                ->where('is_published', true);

            // Filter by prefix query parameter if provided
            if ($request->has('prefix')) {
                $prefix = $request->query('prefix');
                $query->whereHas('subject', function ($q) use ($prefix) {
                    $q->where('slug', $prefix)
                      ->orWhere('name', 'like', '%' . $prefix . '%');
                });
            }

            // Optional category filter query parameter
            if ($request->filled('category')) {
                $categoryFilter = $request->query('category');
                $query->where(function ($q) use ($categoryFilter) {
                    $q->where('category', $categoryFilter)
                      ->orWhere('category', 'like', '%' . $categoryFilter . '%');
                });
            }

            $pages = $query->get();

            // Build individual subjects list with category details
            $subjectsList = [];
            $categoriesGrouped = [];

            foreach ($pages as $page) {
                $catName = trim((string)($page->category ?: 'General'));
                $catSlug = Str::slug($catName);

                $subjectItem = [
                    'id'            => $page->id,
                    'title'         => $page->hero_heading ?: $page->meta_title,
                    'slug'          => $page->slug,
                    'category'      => $catName,
                    'category_slug' => $catSlug,
                    'hasSubmenu'    => false,
                    'children'      => [],
                ];

                $subjectsList[] = $subjectItem;

                if (!isset($categoriesGrouped[$catSlug])) {
                    $categoriesGrouped[$catSlug] = [
                        'id'         => $catSlug,
                        'name'       => $catName,
                        'title'      => $catName,
                        'slug'       => $catSlug,
                        'hasSubmenu' => true,
                        'count'      => 0,
                        'subjects'   => [],
                        'children'   => [],
                    ];
                }

                $categoriesGrouped[$catSlug]['count']++;
                $categoriesGrouped[$catSlug]['subjects'][] = $subjectItem;
                $categoriesGrouped[$catSlug]['children'][] = $subjectItem;
            }

            // Sort subjects list alphabetically by title for /subjects page
            usort($subjectsList, fn($a, $b) => strcasecmp($a['title'], $b['title']));

            // Sort grouped categories alphabetically by name
            $categoriesArray = array_values($categoriesGrouped);
            usort($categoriesArray, fn($a, $b) => strcasecmp($a['name'], $b['name']));

            // If client explicitly requests grouped/tree view for navbar
            if ($request->boolean('grouped') || $request->query('view') === 'navbar' || $request->query('view') === 'tree') {
                return response()->json([
                    'status'     => 'success',
                    'success'    => true,
                    'data'       => $categoriesArray,
                    'categories' => $categoriesArray,
                    'total'      => count($subjectsList),
                ]);
            }

            return response()->json([
                'status'     => 'success',
                'success'    => true,
                'data'       => $subjectsList,
                'categories' => $categoriesArray,
                'total'      => count($subjectsList),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'success' => false,
                'message' => 'Failed to retrieve subject pages: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get distinct active subject categories list.
     */
    public function categories()
    {
        try {
            SubjectPageController::ensureCategoryColumn();

            $existing = SubjectPage::where('is_published', true)
                ->whereNotNull('category')
                ->where('category', '!=', '')
                ->distinct()
                ->pluck('category')
                ->toArray();

            $defaults = SubjectPageController::getDefaultCategories();

            $categories = collect(array_merge($defaults, $existing))
                ->map(fn($c) => trim((string)$c))
                ->filter()
                ->unique(fn($c) => strtolower($c))
                ->sort(fn($a, $b) => strcasecmp($a, $b))
                ->map(fn($c) => [
                    'name' => $c,
                    'slug' => Str::slug($c),
                ])
                ->values();

            return response()->json([
                'status'  => 'success',
                'success' => true,
                'data'    => $categories,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'success' => false,
                'message' => 'Failed to retrieve categories: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Return full details for a published subject page by slug.
     * Includes associated experts and reviews.
     */
    public function show($slug)  
    {
        try {
            SubjectPageController::ensureCategoryColumn();
            $slug = urldecode($slug);

            $page = SubjectPage::with('subject')
                ->where(function ($query) use ($slug) {
                    $query->where('slug', $slug)
                          ->orWhere('slug', 'like', '%/' . $slug);
                })
                ->where('is_published', true)
                ->first();

            if (!$page) {
                return response()->json([
                    'success' => false,
                    'message' => 'Subject page not found',
                ], 404);
            }

            if (empty($page->category)) {
                $page->category = 'General';
            }

            // Fetch related experts and reviews
            $experts = $page->selectedExperts()->map(function ($expert) {
                // Return absolute image URL
                if (!empty($expert->image) && !str_starts_with($expert->image, 'http')) {
                    $expert->image = request()->root() . '/' . ltrim($expert->image, '/');
                }
                return $expert;
            });

            $reviews = $page->selectedReviews();

            return response()->json([
                'success' => true,
                'data'    => [
                    'page'    => $page,
                    'experts' => $experts,
                    'reviews' => $reviews,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }
}
