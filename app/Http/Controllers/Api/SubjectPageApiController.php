<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubjectPage;
use Illuminate\Http\Request;

class SubjectPageApiController extends Controller
{
    /**
     * Get list of all published dynamic subject pages.
     */
    public function index(Request $request)
    {
        try {
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

            $pages = $query->get();

            $parents = [];
            $childrenByParent = [];

            foreach ($pages as $page) {
                $slug = trim($page->slug, '/');
                $segments = explode('/', $slug);

                if (count($segments) === 2) {
                    $parentId = $segments[1];
                    $parents[$parentId] = [
                        'id' => $page->id,
                        'title' => $page->hero_heading,
                        'slug' => end($segments),
                        'hasSubmenu' => false,
                        'children' => [],
                        'order' => $page->id,
                    ];
                } elseif (count($segments) === 3) {
                    $parentId = $segments[1];
                    $childId = $segments[2];

                    $childrenByParent[$parentId][] = [
                        'id' => $page->id,
                        'title' => $page->hero_heading,
                        'slug' => end($segments),
                        'order' => $page->id,
                    ];
                }
            }

            // Auto-create parents for children that do not have an explicit parent page in DB
            foreach ($childrenByParent as $parentId => $children) {
                if (!isset($parents[$parentId])) {
                    $prefix = 'subject';
                    if (!empty($children)) {
                        $childSlug = trim($children[0]['slug'], '/');
                        $parts = explode('/', $childSlug);
                        if (count($parts) > 0) {
                            $prefix = $parts[0];
                        }
                    }

                    $parents[$parentId] = [
                        'id' => $parentId,
                        'title' => ucwords(str_replace('-', ' ', $parentId)),
                        'slug' => $parentId,
                        'hasSubmenu' => true,
                        'children' => [],
                        'order' => 0,
                    ];
                }
            }

            foreach ($parents as $parentId => &$parent) {
                if (isset($childrenByParent[$parentId])) {
                    usort($childrenByParent[$parentId], function($a, $b) {
                        return $a['order'] <=> $b['order'];
                    });

                    $parent['children'] = array_map(function($child) {
                        unset($child['order']);
                        return $child;
                    }, $childrenByParent[$parentId]);

                    $parent['hasSubmenu'] = true;
                } else {
                    $parent['hasSubmenu'] = false;
                    $parent['children'] = [];
                }
            }
            unset($parent);

            usort($parents, function($a, $b) {
                return $a['order'] <=> $b['order'];
            });

            $formattedData = array_map(function($parent) {
                unset($parent['order']);
                return $parent;
            }, $parents);

            return response()->json([
                'status' => 'success',
                'data' => $formattedData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve subject pages: ' . $e->getMessage()
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
            $slug = urldecode($slug);

            $page = SubjectPage::with('subject')
                ->where(function ($query) use ($slug) {
                    $query->where('slug', $slug)
                          ->orWhere('slug', 'like', '%/' . $slug);
                })
                ->where('is_published', true)
                ->first();

            if ($page) {
                $segments = explode('/', trim($page->slug, '/'));
                $page->slug = end($segments);
            }

            if (!$page) {
                return response()->json([
                    'success' => false,
                    'message' => 'Subject page not found',
                ], 404);
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
